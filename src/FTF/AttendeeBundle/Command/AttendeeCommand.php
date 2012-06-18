<?php
namespace FTF\AttendeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AttendeeCommand extends ContainerAwareCommand
{
    protected $eventId;
    protected $amiandoapikey;
    protected $tickets;
    protected $container, $em, $ar;
    
    protected function configure()
    {
        $this
            ->setName('attendee:load')
            ->setDescription('Load attendee from amiando')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();
        $this->eventId = $this->container->getParameter('eventid');
        $this->amiandoapikey = $this->container->getParameter('amiandoapikey');
        
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $this->ar = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:Attendee');
        $this->prepareEnviroment($output);
        $this->loadAttendees($output);
    }
    
    protected function prepareEnviroment(OutputInterface $output)
    {
        $this->tickets = json_decode(file_get_contents('http://www.amiando.com/api/ticket/find/?apikey='. $this->amiandoapikey .'&version=1&format=json&eventId=' . $this->eventId), true);
        if($this->tickets['ids'])
        {
            $this->tickets = $this->tickets['ids'];
            if(count($this->tickets) > 0){
                $output->writeln('<info>Found ' . count($this->tickets) . ' tickets</info>');
                $this->ar->clear();
                return true;
            }
        }
        return false;
    }
    
    protected function loadAttendees(OutputInterface $output)
    {
        $noTwitter = 0;
        foreach($this->tickets as $ticket){
            $attendee = json_decode(file_get_contents('http://www.amiando.com/api/ticket/' . $ticket . '/?apikey='. $this->amiandoapikey .'&version=1&format=json'));
            $att = new \FTF\AttendeeBundle\Entity\Attendee();
            foreach ($attendee->ticket->userData as $data)
            {
                if($data->title == 'Twitter' and strlen($data->value)>0)
                {
                    $att->setAmiandoid($ticket);
                    $att->setEmail($attendee->ticket->email);
                    $att->setTwitter($data->value);
                    $att->setName($attendee->ticket->firstName);
                    $att->setSurname($attendee->ticket->lastName);
                    $this->em->persist($att);
                }else{
                    $noTwitter++;
                }
            }
        }
        $this->em->flush();
        $output->writeln('<warning>Attendees with no twitter: ' . $noTwitter . '</warning>');
        $output->writeln('<info>Attendees loaded: ' . (count($this->tickets) - $noTwitter) . '</info>');
    }
}