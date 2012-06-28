<?php
namespace FTF\AttendeeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use FTF\AttendeeBundle\Entity\Event;

class LoadEventData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $event = new Event();
        $event->setName('FTF 2012');
        $event->setAmiandosecret('https://www.amiando.com/ftf2012/reports/twitter_handles.csv?security=oBdSONjFgL');
        $manager->persist($event);
        
        $manager->flush();
        
        $this->addReference('ftf2012', $event);
    }
    
    /**
     * Set order of fixture
     * 
     * @return int 
     */
    public function getOrder()
    {
        return 1;
    }
}