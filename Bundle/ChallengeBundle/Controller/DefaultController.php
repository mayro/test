<?php

namespace Adidas\Bundle\ChallengeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Adidas\Bundle\PostBundle\Command\OAuth\TwitterOauth;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthSignatureMethodHMACSHA1;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthConsumer;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthUtil;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthToken;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthRequest;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{    
    public function indexAction($name)
    {
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $member = $this->get('adidas.repository.challenge')                
        ->getAll();
    }
    
    public function challengesAction()
    {
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $challenge = $this->get('adidas.repository.challenge')                
                       ->getAvantChallenge();
        if(empty($challenge)) {
             $challenge = $this->get('adidas.repository.challenge')->getlastActiveChallenge();
        }
        $nbMember = $this->get('adidas.repository.challenge.member')                
                       ->countParticipantChallenge($challenge[0]->getId());
        $challenges = $this->get('adidas.repository.challenge')
                       ->getActiveChallenges();
        if(empty($challenges)) {
            throw new \LogicException('aucun challenge active pour '.$branding['club']);
        }
        return $this->render('AdidasChallengeBundle:Challenge:challenges.html.twig', array('challenge' => $challenge, 'nbMember' => $nbMember, 'challenges' => $challenges));
    }
    
    public function challengeDetailAction(Request $request)
    {
        $id_member = $this->container->get('session')->get('user');
        if(empty($id_member)) {
            throw new \LogicException('Veuillez se reconnecter pour accéder à cette page');
        }
        $publish = null;
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id')?$request->get('id'):$this->container->get('session')->get('id');
        $valid = $request->get('particip');
        $session = $this->container->get('session');
        $session->set('id', $id);
        if($valid && $id && $session->get('source') != 'vide') {
            $url = $this->socialConnect($session->get('source')); 
            return $this->redirect($url);
        }
        if(!$valid && !$request->get('id')) {
            //sauvegarder id challenge
            $id = $session->get('id'); 
            if($session->get('source') == 'twitter' || $session->get('source') == 'instagram')
                $publish = $this->publishPostMember($session->get('source'), $id);
        }
        $challenge = $this->get('adidas.repository.challenge')                
                       ->getChallenge($id);
        if(empty($challenge)) {
             throw new \LogicException('challenge erreur ');
        }
        $chal = $challenge[0];
        if($valid && $id  && $session->get('source') == 'vide') {
            $publish = $this->get('adidas.repository.challenge.member')                
                        ->InsertNewParticipVide($id_member, $chal->getPts(), $chal->getId(),$id_member);
        }
        $session->set('source',$chal->getTypeReseau());
        $posts = $this->get('adidas.repository.post')                
                       ->getPostsByChallenge($id);
        
        return $this->render('AdidasChallengeBundle:Challenge:detailChallenge.html.twig', array('challenge' => $challenge, 'posts' => $posts, 'publish' => $publish));
    }
    
    public function socialConnect($source) {
        if($source == 'twitter') {
            $url = $this->connectTwitter();
        }
        elseif ($source == 'instagram') {
            $url = $this->connectInstagram();
        }
        else {
            throw new \LogicException('erreur source challenge'.$source);
        }
        return  $url;
        
    }
    
    public function connectTwitter()
    {
        $consumer_key = $this->container->getParameter('consumer_key_adidadev');
        $consumer_secret = $this->container->getParameter('consumer_secret_adidadev');
        $oauth_callback = $this->container->getParameter('oauth_callback');
        $twitterOauth = new TwitterOauth($consumer_key,$consumer_secret);
        $parameters = array();
        $parameters['oauth_callback'] = $oauth_callback;
        $format = "json";
        if (strrpos($twitterOauth->getUrlRequestToken(), 'https://') !== 0 && strrpos($twitterOauth->getUrlRequestToken(), 'http://') !== 0) {
            $url = "{$twitterOauth->host}{$twitterOauth->getUrlRequestToken()}.{$format}";
        }
        $request = $twitterOauth->signRequestOauth($twitterOauth->getUrlRequestToken(),$parameters);
        $response = $twitterOauth->getCurl($request);
        $request_token = OAuthUtil::parse_parameters($response);
        $this->container->get('session')->set('oauth_token', $request_token['oauth_token']);
        $this->container->get('session')->set('oauth_token_secret', $request_token['oauth_token_secret']);
        $token = $this->container->get('session')->get('oauth_token');
        switch ($twitterOauth->http_code) {
            case 200:
                $url = "https://api.twitter.com/oauth/authorize?oauth_token={$token}";
                return $url;
            default:
                return false;
        } 
    } 
    
    public function publishPostMember($source, $idChallenge) 
    {
        $points = $this->container->getParameter('points');
        if($source == 'twitter') {
            $sha1_method = new OAuthSignatureMethodHMACSHA1();
            $consumer = new OAuthConsumer($this->container->getParameter('consumer_key_adidadev'), $this->container->getParameter('consumer_secret_adidadev'));
            $token = new OAuthConsumer($this->container->get('session')->get('oauth_token'), $this->container->get('session')->get('oauth_token_secret'));
            $parameters = array();
            $parameters['oauth_verifier'] = $_REQUEST['oauth_verifier'];
            $url = 'https://api.twitter.com/oauth/access_token';
            $request = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url, $parameters);
            $request->sign_request($sha1_method, $consumer, $token);

            $http_info = array();
            $ci = curl_init();
            /* Curl settings */
            curl_setopt($ci, CURLOPT_USERAGENT, 'TwitterOAuth v0.2.0-beta2');
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ci, CURLOPT_TIMEOUT, 30);
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ci, CURLOPT_URL, $request->to_url());
            $response = curl_exec($ci);
            $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
            $http_info = array_merge($http_info, curl_getinfo($ci));
            curl_close ($ci);
            //
            $request = $response;
            $token_credentials = OAuthUtil::parse_parameters($request);
            $token = new OAuthConsumer($token_credentials['oauth_token'], $token_credentials['oauth_token_secret']);

            $url = "https://api.twitter.com/1.1/statuses/update.json?status=Posting%20from%20%40myApplicationTwitter%20Twitter".uniqid() ;
            $reqst = OAuthRequest::from_consumer_and_token($consumer, $token, 'POST', $url, array());
            $reqst->sign_request($sha1_method, $consumer, $token);
            $http_info = array();
            $ci = curl_init();
            /* Curl settings */
            curl_setopt($ci, CURLOPT_USERAGENT, 'TwitterOAuth v0.2.0-beta2');
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ci, CURLOPT_TIMEOUT, 30);
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if (!empty($postfields)) {
                curl_setopt($ci, CURLOPT_POSTFIELDS, $reqst->to_postdata());
            }
            curl_setopt($ci, CURLOPT_URL, $reqst->to_url());
            $response = curl_exec($ci);
            $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
            $http_info = array_merge($http_info, curl_getinfo($ci));
            curl_close ($ci);
            $resp =  json_decode($response);
            if(!empty($resp->errors))
                return FALSE;
        }
        elseif($source == 'instagram') {
            if($_GET['code']) {
                $code = $_GET['code'];
                $url = "https://api.instagram.com/oauth/access_token";
                $access_token_parameters = array(
                        'client_id'                =>     $this->container->getParameter('client_id_inst_adidadev'),
                        'client_secret'            =>     $this->container->getParameter('client_secret_inst_adidadev'),
                        'grant_type'               =>     'authorization_code',
                        'redirect_uri'             =>     $this->container->getParameter('oauth_callback_inst_adidadev'),
                        'code'                     =>     $code
                );
                $curl = curl_init($url);    // we init curl by passing the url
                curl_setopt($curl,CURLOPT_POST,true);   // to send a POST request
                curl_setopt($curl,CURLOPT_POSTFIELDS,$access_token_parameters);   // indicate the data to send
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   // to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   // to stop cURL from verifying the peer's certificate.
                $result = curl_exec($curl);   // to perform the curl session
                curl_close($curl);   // to close the curl session
                $resp = json_decode($result,true);
                if(!empty($arr['error_type'])) 
                    return FALSE;
            }
        }
        $id_member = $this->container->get('session')->get('user');
        if(empty($id_member)) {
            throw new \LogicException('Veuillez se reconnecter pour accéder à cette page');
        }
        $member = $this->get('adidas.repository.challenge.member')                
                       ->InsertNewParticipMember($resp, $source, $points, $idChallenge, $id_member);
        if($member) return TRUE;
    }
    
    public function connectInstagram() 
    {
        $consumer_key = $this->container->getParameter('client_id_inst_adidadev');
        $consumer_secret_key = $this->container->getParameter('client_secret_inst_adidadev');
        $callback = $this->container->getParameter('oauth_callback_inst_adidadev');
        $url = "https://api.instagram.com/oauth/authorize/?client_id=".$consumer_key."&amp;redirect_uri=".$callback."&amp;response_type=code";
        return $url;
    }
    
    public function classementAction($id=null) 
    {
        $BestMonth = NULL;
        $type_challenge = NULL;
        $challenges = $this->get('adidas.repository.challenge')
                       ->getActiveClassementChallenges();
        $number = $this->get('adidas.repository.challenge.member')
                       ->getCountMemberByClub($id);
        $best = $this->get('adidas.repository.challenge.member')
                       ->getBestTenByClub($id);
        $last = $this->get('adidas.repository.challenge.member')
                ->getLastMemberByClub($id);
        $particpants = $this->checkUserClassement($id);
        if($id) {
            $type_challenge = $this->get('adidas.repository.challenge')
                ->getChallenge($id)[0]->getTypeChallenge()    ;
        }
        if(!$id || $type_challenge == 'permanent') {
            $month = $this->get('adidas.repository.previous.month')
                    ->getBestMonth($id);
        }
        if(isset($month)) {
            $BestMonth = $this->get('adidas.repository.challenge.member')->getInfoMember($month['userMax']);
        }
        return $this->render('AdidasChallengeBundle:Challenge:classement.html.twig', array('challenges' => $challenges, 'participants' => $number, 'best' => $best, 'last' => $last, 'bestParticipants' => $particpants, 'bestMonth' =>$BestMonth, 'pointsMonth' => isset($month) ? $month['MaxPoints'] : null));
    }
    
    // if user in the best ten users
    public function checkUserClassement($id=null)
    {
        $particpants = array();
        $found = 0;
        $idUser = $this->container->get('session')->get('user');
        if(empty($idUser)) {
            throw new \LogicException('Veuillez se reconnecter pour accéder à cette page');
        }
        $users = $this->get('adidas.repository.challenge.member')
                ->getBestByClub($id);
       foreach ($users as $key=>$user) {
           if($key <9) {
               if($user['id'] == $idUser) 
                   $found = 1;
               $particpants[] = $user;
           }
           elseif($key == 9 && $found == 1) {
               $particpants[] = $user;
               return $particpants;
           }
           elseif($key>=9 && $found ==0) {
               if($user['id'] == $idUser) 
               {
                   $user['count'] = $key;
                   $particpants[] = $user;
                   $found=1;
               }
               elseif($user['id'] != $idUser && $key==9) {
                   $last = $user;
               }
           }
        }   
        if(!empty($last) && count($particpants) == 9) $particpants[] = $last;
        return $particpants;
    }
            
}