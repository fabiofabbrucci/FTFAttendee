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
            $stack_account = array();
            while (($data = fgetcsv($handle, 1000, ";") ) !== FALSE) {
                if($first_line){
                    $first_line = !$first_line;
                    continue;
                }
                if (strlen($data[3]) and $count < 1000) {
                    $twitter = trim($data[3]);
                    if(substr($twitter, 0, 1) == '@'){
                        $twitter = substr($twitter, 1);
                    }
                    if(in_array($twitter, $stack_account)){
                        continue;
                    }else{
                        $stack_account[] = $twitter;
                    }
                    $user = $this->ur->findOneByTwitter($twitter);
                    if(!$user){
                        $user = new User();
                        $user->setName($data[0]);
                        $user->setSurname($data[1]);
                        $twitter = $user->setTwitter($twitter);
                        $this->em->persist($user);
                    }else{
                        $twitter = $user->getTwitter();
                    }
                    
                    if(isset($user)){
                        $att = new Attendee();
                        $att->setUser($user);
                        $att->setEvent($event);
                        $this->em->persist($att);
                        $this->em->flush();
                    }
                    $count++;
                    $output->writeln("<info>$count) " . $twitter . "</info>");
                }
            }
            $output->writeln("<info>loaded $count users</info>");
        }
    }
}