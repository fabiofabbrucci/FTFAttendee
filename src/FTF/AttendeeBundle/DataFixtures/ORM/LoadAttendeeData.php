<?php
namespace FTF\AttendeeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use FTF\AttendeeBundle\Entity\Attendee;

class LoadAttendeeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $attendee = new Attendee();
        $attendee->setName('Fabio');
        $attendee->setSurname('Fabbrucci');
        $attendee->setTwitter('Fabbrucci');

        $manager->persist($attendee);
        $manager->flush();
    }
}