<?php
namespace FTF\AttendeeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use FTF\AttendeeBundle\Entity\User;
use FTF\AttendeeBundle\Entity\Organizator;

class LoadOrganizatorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $organizers = array(
            'fabbrucci',
            'salvini',
            'curcio', 
            'sessa', 
            'cedaro', 
            'angelini', 
            'rodriguez', 
        );
        
        foreach($organizers as $organizer)
        {
            $org = new Organizator();
            $org->setUser($this->getReference($organizer));
            $org->setEvent($this->getReference('ftf2012'));
            $manager->persist($org);
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
        return 3;
    }
}