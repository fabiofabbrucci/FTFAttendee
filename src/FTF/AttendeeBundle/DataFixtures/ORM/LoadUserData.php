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
        $users = array(
            array(
                'twitter' => 'Fabbrucci',
                'name' => 'Fabio',
                'surname' => 'Fabbrucci'),
            array(
                'twitter' => 'LucaSalvini',
                'name' => 'Luca',
                'surname' => 'Salvini'),
            array(
                'twitter' => 'anSeamRock',
                'name' => 'Rocco',
                'surname' => 'Curcio'),
            array(
                'twitter' => 'dieSignIt',
                'name' => 'Diego',
                'surname' => 'Sessa'),
            array(
                'twitter' => 'cedmax',
                'name' => 'Marco',
                'surname' => 'Cedaro'),
            array(
                'twitter' => 'jurgzs',
                'name' => 'Marco',
                'surname' => 'Angelini'),
            array(
                'twitter' => 'erodrix',
                'name' => 'Emanuele',
                'surname' => 'Rodriguez'),
            array(
                'twitter' => 'jaffathecake',
                'name' => 'Jake',
                'surname' => 'Archibald'),
            array(
                'twitter' => 'aral',
                'name' => 'Aral',
                'surname' => 'Balkan'),
            array(
                'twitter' => 'blaine',
                'name' => 'Blaine',
                'surname' => 'Cook'),
            array(
                'twitter' => 'denisejacobs',
                'name' => 'Denise',
                'surname' => 'Jacobs'),
            array(
                'twitter' => 'ppk',
                'name' => 'Peter-Paul',
                'surname' => 'Koch'),
            array(
                'twitter' => 'skrug',
                'name' => 'Steve',
                'surname' => 'Krug'),
            array(
                'twitter' => 'mishunov',
                'name' => 'Denys',
                'surname' => 'Mishunov'),
            array(
                'twitter' => 'hyper_linda',
                'name' => 'Linda',
                'surname' => 'Sandvik'),
            array(
                'twitter' => 'rem',
                'name' => 'Remy',
                'surname' => 'Sharp'),
            array(
                'twitter' => 'snookca',
                'name' => 'Jonathan',
                'surname' => 'Snook'),
        );
        
        foreach($users as $user){
            $us = $user['twitter'];
            $$us = new User();
            $$us->setName($user['name']);
            $$us->setSurname($user['surname']);
            $$us->setTwitter($us);
            $manager->persist($$us);
            $this->addReference($us, $$us);
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
        return 2;
    }
}