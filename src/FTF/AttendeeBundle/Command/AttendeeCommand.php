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
        $this->loadAttendeeFromCsv($output);
    }

    protected function loadAttendeeFromCsv(OutputInterface $output)
    {
        $count = 0;
        $first_line = true;
        if (($handle = fopen('https://www.amiando.com/ftf2012/reports/twitter_handles.csv?security=oBdSONjFgL', "r")) !== FALSE) {
            $this->ar->clear();
            while (($data = fgetcsv($handle, 1000, ";") ) !== FALSE) {
                if($first_line){
                    $first_line = !$first_line;
                    continue;
                }
                if (strlen($data[3])) {
                    $att = new \FTF\AttendeeBundle\Entity\Attendee();
                    $att->setName($data[0]);
                    $att->setSurname($data[1]);
                    $att->setTwitter($data[3]);
                    if($att->isTwitterAccountValid())
                    {
                        $this->em->persist($att);
                        $count++;
                    }
                }
            }
            $this->em->flush();
            $output->writeln("<info>loaded $count users</info>");
        }
    }
}