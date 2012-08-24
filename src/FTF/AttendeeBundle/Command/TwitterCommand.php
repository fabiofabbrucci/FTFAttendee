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

class TwitterCommand extends ContainerAwareCommand
{
    protected $eventId;
    protected $amiandoapikey;
    protected $tickets;
    protected $container, $em, $ar, $ur, $er;

    protected function configure()
    {
        $this
                ->setName('attendee:twitter')
                ->setDescription('Load twitterid')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->container = $this->getApplication()->getKernel()->getContainer();

        $this->em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $this->ar = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:Attendee');
        $this->ur = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:User');
        $this->er = $this->getContainer()->get('doctrine')->getRepository('FTFAttendeeBundle:Event');
        $this->loadTwitter($output);
    }

    protected function loadTwitter(OutputInterface $output)
    {
        $users = $this->ur->findAll();
        foreach($users as $index=>$user){
            if($user->getTwitter() and !$user->getTwitterid()){
                if($twitterid = $user->getTwitteridFromTwitter()){
                    $user->setTwitterid($twitterid);
                    $this->em->persist($user);
                    $this->em->flush();
                    $output->writeln("<info>loaded ".$user->getTwitter()." with $twitterid</info>");
                }else{
                    $output->writeln("<error>problem with  ".$user->getTwitter()."</error>");
                }
            }
            
        }
    }
}