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
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findByEvent($event->getId());
        $organizators = $em->getRepository('FTFAttendeeBundle:Organizator')
                ->findByEvent($event->getId());
        $speakers = $em->getRepository('FTFAttendeeBundle:Speaker')
                ->findByEvent($event->getId());
        $users = array_merge($attendees, $organizators, $speakers);
        
        usort($users, function ($a, $b){
            return strcmp(strtolower($a->getTwitter()), strtolower($b->getTwitter()));
        });
        
        //shuffle($users);
        return array('attendees' => $users);
    }

    private function getFollowers($username)
    {
        if($content = file_get_contents('https://api.twitter.com/1/followers/ids.json?screen_name=' . $username)) {
            $followers = json_decode($content);
            return $followers->ids;
        }else{
            return false;
        }
    }                
    
    private function getFriends($username)
    {
        if($content = file_get_contents('https://api.twitter.com/1/friends/ids.json?screen_name=' . $username)) {
            $followers = json_decode($content);
            return $followers->ids;
        }else{
            return false;
        }
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
     */
    public function ajaxAction($username)
    {
        if(substr($username, 0, 1) == '@'){
            $username = substr($username, 1);
        }
        $followers = $this->getFollowers($username);
        $friends = $this->getFriends($username);
        if($followers == false or $friends == false){
            return array(
                'attendees' => array(),
                'username' => $username,
                'error' => true
            );
        }
        
        $all = array_unique(array_merge($followers, $friends));
        
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findAllByTwitteridAndEvent($all, $event);
        $organizators = $em->getRepository('FTFAttendeeBundle:Organizator')
                ->findAllByTwitteridAndEvent($all, $event);
        $speakers = $em->getRepository('FTFAttendeeBundle:Speaker')
                ->findAllByTwitteridAndEvent($all, $event);
        $users = array_merge($attendees, $organizators, $speakers);
        usort($users, function ($a, $b){
            return strcmp(strtolower($a->getTwitter()), strtolower($b->getTwitter()));
        });
        
        $user = $em->getRepository('FTFAttendeeBundle:User')->findOneByTwitter($username);
        
        $param = array(
            'attendees' => $users,
            'username' => $username,
            'registered' => (bool)$user,
            'error' => false
        );
        
        $response = $this->render('FTFAttendeeBundle:Default:ajax.html.twig', $param);
        $response->setSharedMaxAge(60*60*24);

        return $response;
    }
}