<?php

namespace FTF\AttendeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FTF\AttendeeBundle\Entity\Organizator
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FTF\AttendeeBundle\Entity\OrganizatorRepository")
 */
class Organizator
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setuser(User $user)
    {
        $this->user = $user;
    }
    
    public function getName()
    {
        return $this->user->getName();
    }
    
    public function getSurname()
    {
        return $this->user->getSurname();
    }
    
    public function getTwitter()
    {
        return $this->user->getTwitter();
    }
    
    public function getTwitterid()
    {
        return $this->user->getTwitterid();
    }
}