<?php
namespace FTF\AttendeeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use FTF\AttendeeBundle\Entity\User;
use FTF\AttendeeBundle\Entity\Organizator;
use FTF\AttendeeBundle\Entity\Speaker;

class LoadSpeakerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $speakers = array(
            'jaffathecake',
            'aral',
            'blaine',
            'denisejacobs',
            'ppk',
            'skrug',
            'mishunov',
            'hyper_linda',
            'rem',
            'snookca',
        );
        
        foreach($speakers as $speaker)
        {
            $sp = new Speaker();
            $sp->setUser($this->getReference($speaker));
            $sp->setEvent($this->getReference('ftf2012'));
            $manager->persist($sp);
        }
        
        $manager->flush();
    }
    
    /**
     * Set order of fixture
     * 
     * @return int 
     */
    public function getOrder()
    {
        return 4;
    }
}