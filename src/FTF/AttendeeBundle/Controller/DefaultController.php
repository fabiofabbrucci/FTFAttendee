<?php

namespace FTF\AttendeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        if($username = $request->get('username')){
            return $this->redirect($this->generateUrl('search', array('username' => $username)));
        }

        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findByEvent($event->getId());
        return array('attendees' => $attendees);
    }

    private function getFollowers($username)
    {
        $followers = json_decode(file_get_contents('https://api.twitter.com/1/followers/ids.json?screen_name=' . $username));
        return $followers->ids;
    }                
    
    private function getFriends($username)
    {
        $followers = json_decode(file_get_contents('https://api.twitter.com/1/friends/ids.json?screen_name=' . $username));
        return $followers->ids;
    }                
    
    /**
     * @Route("/search/{username}", name="search")
     * @Template()
     */
    public function searchAction($username)
    {
        return array(
            'username' => $username,
        );
    }
    
    /**
     * @Route("/ajax/search/{username}", name="ajax")
     * @Template()
     * @Cache(expires="tomorrow")
     */
    public function ajaxAction($username)
    {
        $followers = $this->getFollowers($username);
        $friends = $this->getFriends($username);
        
        $all = array_unique(array_merge($followers, $friends));
        
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findAllByTwitteridAndEvent($all, $event);
        return array(
            'attendees' => $attendees,
            'username' => $username,
        );
    }
}
