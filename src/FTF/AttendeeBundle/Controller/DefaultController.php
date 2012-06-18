<?php

namespace FTF\AttendeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findAll();
        return array('attendees' => $attendees);
    }
}
