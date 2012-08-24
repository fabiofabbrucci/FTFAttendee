<?php
namespace FTF\AttendeeBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use FTF\AttendeeBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $fabbrucci = new User();
        $fabbrucci->setName('Fabio');
        $fabbrucci->setSurname('Fabbrucci');
        $fabbrucci->setTwitter('Fabbrucci');
        $manager->persist($fabbrucci);
        
        $salvini = new User();
        $salvini->setName('Luca');
        $salvini->setSurname('Salvini');
        $salvini->setTwitter('LucaSalvini');
        $manager->persist($salvini);
        
        $curcio = new User();
        $curcio->setName('Rocco');
        $curcio->setSurname('Curcio');
        $curcio->setTwitter('anSeamRock');
        $manager->persist($curcio);
        
        $sessa = new User();
        $sessa->setName('Diego');
        $sessa->setSurname('Sessa');
        $sessa->setTwitter('dieSignIt');
        $manager->persist($sessa);
        
        $cedaro = new User();
        $cedaro->setName('Marco');
        $cedaro->setSurname('Cedaro');
        $cedaro->setTwitter('cedmax');
        $manager->persist($cedaro);
        
        $angelini = new User();
        $angelini->setName('Marco');
        $angelini->setSurname('Angelini');
        $angelini->setTwitter('jurgzs');
        $manager->persist($angelini);
        
        $rodriguez = new User();
        $rodriguez->setName('Emanuele');
        $rodriguez->setSurname('Rodriguez');
        $rodriguez->setTwitter('erodrix');
        $manager->persist($rodriguez);
        
        $manager->flush();
        
        $this->addReference('fabbrucci', $fabbrucci);
        $this->addReference('salvini', $salvini);
        $this->addReference('curcio', $curcio);
        $this->addReference('sessa', $sessa);
        $this->addReference('cedaro', $cedaro);
        $this->addReference('angelini', $angelini);
        $this->addReference('rodriguez', $rodriguez);
        
    }
    
    /**
     * Set order of fixture
     * 
     * @return int 
     */
    public function getOrder()
    {
        return 2;
    }
}