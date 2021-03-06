<?php

namespace FTF\AttendeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Route("/search", name="index_search")
     * @Method("get")
     * @Template()
     * @Cache(expires="+1 hours")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                ->findByEvent($event);
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
            
            $kernel = $this->get('kernel');
            $logger = $this->get('logger');
            $logger->pushHandler(new StreamHandler($kernel->getLogDir() . '/twitter.log', Logger::INFO));
            $logger->err('Reached Limit loooking for ['.$username.']');
            
            return false;
        }
    }                
    
    private function getFriends($username)
    {
        if($content = file_get_contents('https://api.twitter.com/1/friends/ids.json?screen_name=' . $username)) {
            $followers = json_decode($content);
            return $followers->ids;
        }else{
            
            $kernel = $this->get('kernel');
            $logger = $this->get('logger');
            $logger->pushHandler(new StreamHandler($kernel->getLogDir() . '/twitter.log', Logger::INFO));
            $logger->err('Reached Limit loooking for ['.$username.']');
            
            return false;
        }
    }                
    
    /**
     * @Route("/search", name="search")
     * @Method("post")
     * @Template()
     */
    public function searchAction()
    {
        $kernel = $this->get('kernel');
        $request = $this->get('request');
        $username = $request->request->get('username');
        
        $logger = $this->get('logger');
        $logger->pushHandler(new StreamHandler($kernel->getLogDir() . '/twitter.log', Logger::INFO));
        $logger->info($username);
        
        if($username){
            return array(
                'username' => $username,
            );
        }else{
            return $this->redirect($this->generateUrl('index'));   
        }
    }
    
    /**
     * @Route("/ajax/search/{username}", name="ajax")
     * @Template()
     * @Cache(expires="+1 hours")
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
        
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FTFAttendeeBundle:Event')
                ->findOneByName('FTF 2012');
        
        for($i=0;$i<2; $i++){
            if($i == 0) $users = $followers;
            else $users = $friends;
            $attendees = $em->getRepository('FTFAttendeeBundle:Attendee')
                    ->findAllByTwitteridAndEvent($users, $event);
            $organizators = $em->getRepository('FTFAttendeeBundle:Organizator')
                    ->findAllByTwitteridAndEvent($users, $event);
            $speakers = $em->getRepository('FTFAttendeeBundle:Speaker')
                    ->findAllByTwitteridAndEvent($users, $event);
            $users = array_merge($attendees, $organizators, $speakers);
            usort($users, function ($a, $b){
                return strcmp(strtolower($a->getTwitter()), strtolower($b->getTwitter()));
            });
            if($i == 0) $followers = $users;
            else $friends = $users;
        }
        $user = $em->getRepository('FTFAttendeeBundle:User')->findOneByTwitter($username);
        
        $param = array(
            'followers' => $followers,
            'friends' => $friends,
            'username' => $username,
            'registered' => (bool)$user,
            'error' => false
        );
        
        return $this->render('FTFAttendeeBundle:Default:ajax.html.twig', $param);
        
        $response = $this->render('FTFAttendeeBundle:Default:ajax.html.twig', $param);
        $response->setETag(md5($response->getContent()));
        $response->setPublic();
        $response->setSharedMaxAge(60*60*24);
        $response->setMaxAge(60*60*24);

        return $response;
    }
}