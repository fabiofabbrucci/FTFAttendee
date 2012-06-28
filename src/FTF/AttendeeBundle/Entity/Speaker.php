<?php

namespace FTF\AttendeeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FTF\AttendeeBundle\Entity\Speaker
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FTF\AttendeeBundle\Entity\SpeakerRepository")
 */
class Speaker
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
}