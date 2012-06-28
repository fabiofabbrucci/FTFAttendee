<?php

namespace FTF\AttendeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FTF\AttendeeBundle\Entity\Attendee;
use FTF\AttendeeBundle\Entity\User;
use FTF\AttendeeBundle\Entity\Event;

class AttendeeCommand extends ContainerAwareCommand
{
    protected $eventId;
    protected $amiandoapikey;
    protected $tickets;
    protected $container, $em, $ar, $ur, $er;

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
        $this->ur = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:User');
        $this->er = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:Event');
        $this->loadAttendeeFromCsv($output);
    }

    protected function loadAttendeeFromCsv(OutputInterface $output)
    {
        $count = 0;
        $first_line = true;
        $event = $this->er->findOneByName('FTF 2012');
        if (($handle = fopen($event->getAmiandosecret(), "r")) !== FALSE) {
            $this->ar->clear();
            while (($data = fgetcsv($handle, 1000, ";") ) !== FALSE) {
                if($first_line){
                    $first_line = !$first_line;
                    continue;
                }
                if (strlen($data[3]) and $count < 10) {
                    $user = new User();
                    $user->setTwitter($data[3]);
                    
                    $user = $this->ur->findOneByTwitter($user->getTwitter());
                    $att = new Attendee();
                    $att->setEvent($event);
                    if($user){
                        $att->setUser($user);
                    }else{
                        $user = new User();
                        $user->setName($data[0]);
                        $user->setSurname($data[1]);
                        $user->setTwitter($data[3]);
                        $user->loadTwitterid();
                        $att->setUser($user);
                        
                        $this->em->persist($user);
                    }
                    $this->em->persist($att);
                    $count++;
                    $output->writeln("<info>$count) " . $user->getTwitter() . "</info>");
                }
            }
            $this->em->flush();
            $output->writeln("<info>loaded $count users</info>");
        }
    }
}