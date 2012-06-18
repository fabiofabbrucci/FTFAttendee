<?php

namespace FTF\AttendeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FTF\AttendeeBundle\Entity\Attendee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FTF\AttendeeBundle\Entity\AttendeeRepository")
 */
class Attendee
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
     * @ORM\Column(name="twitter", type="string", length=255)
     */
    private $twitter;

    /**
     * @var string $amiandoid
     *
     * @ORM\Column(name="amiandoid", type="string", length=255)
     */
    private $amiandoid;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


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
     * Set amiandoid
     *
     * @param string $amiandoid
     */
    public function setAmiandoid($amiandoid)
    {
        $this->amiandoid = $amiandoid;
    }

    /**
     * Get amiandoid
     *
     * @return string 
     */
    public function getAmiandoid()
    {
        return $this->amiandoid;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}