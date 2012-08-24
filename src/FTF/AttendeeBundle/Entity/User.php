<?php

namespace FTF\AttendeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FTF\AttendeeBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="FTF\AttendeeBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string $twitter
     *
     * @ORM\Column(name="twitter", type="string", length=255, unique=true)
     */
    private $twitter;

    /**
     * @var string $twitterid
     *
     * @ORM\Column(name="twitterid", type="string", length=255)
     */
    private $twitterid;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        if(substr($twitter, 0, 1) == '@'){
            $twitter = substr($twitter, 1);
        }
        $this->twitter = $twitter;
    }
    
    public function isTwitterAccountValid()
    {
        if(strpos($this->twitter, '@') !== false)
            return false;
        return true;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }
    
    /**
     * Set twitter
     *
     * @param string $twitter
     */
    public function setTwitterid($twitterid)
    {
        $this->twitterid = $twitterid;
    }
    
    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitterid()
    {
        return $this->twitterid;
    }
    
    public function loadTwitterid()
    {
        if($this->isTwitterAccountValid())
        {
            $url = 'https://api.twitter.com/1/users/lookup.json?screen_name=' . $this->getTwitter();
            if(!in_array($this->get_http_response_code($url),array("404", "400"))){
                $content = file_get_contents($url);
                $twitterUsers = json_decode($content);
                if(count($twitterUsers))
                {
                    $twitterUser = $twitterUsers[0];
                    $this->setTwitterid($twitterUser->id);
                    return true;
                }
            }
        }
        return false;
    }
    
    private function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }
}