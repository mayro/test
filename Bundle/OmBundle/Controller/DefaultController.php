<?php

namespace Adidas\Bundle\OmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Adidas\Bundle\OmBundle\Classes\Tools;
use Adidas\Bundle\OmBundle\Entity\Member;
use Adidas\Bundle\OmBundle\Form\Type\RegisterFormType;
use Adidas\Bundle\OmBundle\Form\Type\AuthenticationFormType;
use Alex\MultisiteBundle\Annotation\Route;
use Adidas\Bundle\ChallengeBundle\Command\ConnectionSocialCommand;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
    public function indexAction(Request $request)
    {
        //die($this->container->get('session')->get('user'));
        if(!empty($this->container->get('session')->get('user'))) {
            return $this->redirect($this->generateUrl('adidas_om_profil', array('id' => $this->container->get('session')->get('user'))));
        }
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $client = new \SoapClient($this->container->getParameter('soap_service_url'));

        $form_auth = $this->createForm(new AuthenticationFormType());
        $form_reg= $this->createForm(new RegisterFormType());

        $form_reg->handleRequest($request);
        $form_auth->handleRequest($request);

        //Authenticate form
        if( $form_auth->isValid() ){
            $data = $form_auth->getData();
            $jersey_id = $data['codeb'];
            $params = array(
                "userToken"         => $this->container->getParameter('userToken'),
                "sessionTokenType"  => array("tokenType"=>"openToken"),
                "siteId"            => $this->container->getParameter('siteId'),
                "email"             => $data['utilb'],
                "password"          => $data['mdpb'],
                "countryOfSite"     => "fr"
            );

            //check valid Jersey
            if($jersey_id != NULL ){
                if( !Tools::isJerseyNumberValid($jersey_id)){
                    $error = "Ce numéro de maillot est invalide";
                    return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
                }
            }else
                $jersey_id = 0;


            $response = $client->__soapCall("authenticate", array($params));
            $conditionCode =  $response->conditionCode;

            $check = Tools::checkAuthConditionCode($conditionCode);
            if($check){
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$check));
            }
            if($conditionCode != "iCCD_AUTH_CHK_0001" )
            {
                $error = "Une erreure c'est produite";
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
            }
            
            $firstName	= $response->firstName;
            $lastName	= $response->lastName;
            $email 		= $data['utilb'];
        }
        //Registration form
        if( $form_reg->isValid() ) {
            $data = $form_reg->getData();
            $jersey_id = $data['code'];

            //General parametre
            $params = array(
                "userToken"         => $this->container->getParameter('userToken'),
                "sessionTokenType"  => array("tokenType"=>"openToken"),
                "siteId"            => $this->container->getParameter('siteId'),
                "countryOfSite"     => "fr",
                "email"             => $data['email'],
                "password"          => $data['mdp'],
                "userName"          => $data['util'],
                "dateOfBirth"       => $data['aaaa'] ."-" . $data['mm'] ."-" . $data['jj'],
                "AMF"               => "N",
                "firstName"         => $data['nom'],
                "lastName"          => $data['prenom'],
                "gender"            => $data['civ'],
                "actionType"        => "REGISTRATION",
            	"source"            => "900"
            );
            
            $params2 = array(
            		"userToken"         => $this->container->getParameter('userToken'),
            		"sessionTokenType"  => array("tokenType"=>"openToken"),
            		"siteId"            => $this->container->getParameter('siteId'),
            		"countryOfSite"     => "fr",
            		"email"             => $data['email'],
            		"userName"          => $data['util'],
            		"dateOfBirth"       => $data['aaaa'] ."-" . $data['mm'] ."-" . $data['jj'],
            		"AMF"               => "N",
            		"firstName"         => $data['nom'],
            		"lastName"          => $data['prenom'],
            		"gender"            => $data['civ'],
            		"newsletterTypeId"  => '40001',
            		"source"            => "900"
            );
            $firstName	= $data['prenom'];
           	$lastName	= $data['nom'];
           	$email 		= $data['email'];
            //Check Password
            if( !Tools::isValidPassword( $data['mdp']) ){
                $error = "Le mot de passe doit faire entre 8 et 12 caractéres et doit contenir au moins une majuscule, une minuscule et un chiffre";
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
            }

            if( !Tools::isValidNameText($data['nom']) || !Tools::isValidNameText($data['prenom']) ){
                $error = "Votre nom et votre prénom doit être constitué d'au moins 2 lettres";
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
            }


            //Check member age
            switch (Tools::getAgeAuthorization($data['jj'], $data['mm'], $data['aaaa'])){
                case "unauthorized"         :   die('Error minor');break;
                case "parent_authorization" :   if( $data['pcf_try'] ){
                                                    die();
                                                }
                                                break;
            }
           //set optin value
            if( $data["optin"])
            {
                $params['AMF'] = "Y";
                $params['Consents']= array(array("ConsentType"=>"AMF","ConsentValue"=>"Y"));
                $params2['AMF'] = "Y";
                $params2['Consents']= array(array("ConsentType"=>"AMF","ConsentValue"=>"Y"));
            }else{
                $params['AMF'] = "N";
                $params['Consents']= array(array("ConsentType"=>"AMF","ConsentValue"=>"N"));
                $params2['AMF'] = "N";
                $params2['Consents']= array(array("ConsentType"=>"AMF","ConsentValue"=>"N"));
            }
            //check valid Jersey
            if($jersey_id != NULL ){
                if( !Tools::isJerseyNumberValid($jersey_id)){
                        $error = "Ce numéro de maillot est invalide";
                        return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error,'fb_share_url'=>'tets'));
                }
            }else
                $jersey_id = 0;
			//Appel web service creatAccount
            $response = $client->__soapCall("CreateAccount", array($params));
            $conditionCode =  $response->conditionCode;

			//Vérification code reponse
            $check = Tools::checkRegConditionCode($conditionCode);
            if($check){
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$check));
            }
            if($conditionCode != "iCCD_CRT_ACCT_0001" )
            {
                $error = "Une erreure c'est produite";
                return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
            }
            $subscription = $client->__soapCall("createSubscription", array($params2));
            $subscriptionCode =  $subscription->conditionCode;
        }

        if( $form_auth->isValid() ||  $form_reg->isValid()){
            $uci =  $response->uci;
            $repository = $this->getDoctrine()
                                ->getRepository('AdidasOmBundle:Member');
            if( $jersey_id ){
                $jersey = $repository->findByJerseyId($jersey_id);
                if(count($jersey)){
                    $error = "Ce numéro de maillot à déja été utilisé";
                    return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView(),'error'=>$error));
                }
            }
            $request = $repository->findByUci($uci);
            //if member exist we update information by UCI
            if(count($request)){
                $member = $request[0];
            }else{
                $member  = new Member ();
                $member->setUci($uci);
            }
            //store entity
            $member->setJerseyId($jersey_id);
            if(empty($data['util'])) {
                $params = array(
                        "userToken"     => $this->container->getParameter('userToken'),
                        "sessionToken"  => $response->sessionToken,
                        "siteId"        => $this->container->getParameter('siteId'),
                        "uci" => $member->getUci(),
                        "includePersonalInformation" => 'Y',
                        "version" => '4.0'
                    );
                $account = $client->__soapCall("lookUpAccount", array($params));
                $name = $account->userName;
                    //insert info in user session 
                $this->container->get('session')->set('userInfo', array('email'=>$account->email, 'nom'=>$account->lastName, 'prenom'=>$account->firstName));
            }
            else {
                $name = $data['util'];
                $this->container->get('session')->set('userInfo', array('email'=>$data['email'], 'nom'=>$data['nom'], 'prenom'=>$data['prenom']));
            }
            $member->setName($name);
            $member->setActive(false);
            $em->persist($member);
            $em->flush();
            //insert id user in session
            $this->container->get('session')->set('user', $member->getId());
            //Welcome email
            //Tools::sendWelcomeEmail($email,$firstName, $lastName, $this->container->getParameter('soap_welcome_email_url'), $this->container->getParameter('soap_welcome_email_url_jsp'));
            return $this->render('AdidasOmBundle:Om:confirm.html.twig',array('uci'=>$uci,'mail'=>$email, 'fb_share_url'=>$this->container->getParameter('fb_share_url')));            
            }
        return $this->render('AdidasOmBundle:Default:index.html.twig',array('form_reg'=>$form_reg->createView(),'form_auth'=>$form_auth->createView()));
    }
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */    
    public function connectionAction(Request $request)
    {
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $client = new \SoapClient($this->container->getParameter('soap_service_url'));

        $form_cnx = $this->createForm(new AuthenticationFormType());
        $form_cnx->handleRequest($request);
        //Authenticate form
        if($request->getMethod() == 'POST') {
            if($form_cnx->isValid() ){
                $data = $form_cnx->getData();
                $params = array(
                    "userToken"         => $this->container->getParameter('userToken'),
                    "sessionTokenType"  => array("tokenType"=>"openToken"),
                    "siteId"            => $this->container->getParameter('siteId'),
                    "email"             => $data['utilb'],
                    "password"          => $data['mdpb'],
                    "countryOfSite"     => "fr"
                );
                $response = $client->__soapCall("authenticate", array($params));
                $conditionCode =  $response->conditionCode;
                $check = Tools::checkAuthConditionCode($conditionCode);
                if($check){
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        $check
                    );
                    return $this->redirect($this->generateUrl('adidas_om_homepage'));
                }
                if($conditionCode != "iCCD_AUTH_CHK_0001" )
                {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'Une erreure c\'est produite' 
                    );
                    return $this->redirect($this->generateUrl('adidas_om_homepage'));
                }
                $uci =  $response->uci;
                $repository = $this->getDoctrine()
                                   ->getRepository('AdidasOmBundle:Member');
                $request = $repository->findByUci($uci);
                if(empty($request)) {
                    throw new \LogicException('pas de member avec cet uci');
                }
                $this->container->get('session')->set('sessionToken',$response->sessionToken);
                $member = $request[0];
                $this->container->get('session')->set('user', $member->getId());
                if(count($request)) {
                    $params = array(
                        "userToken"     => $this->container->getParameter('userToken'),
                        "sessionToken"  => $response->sessionToken,
                        "siteId"        => $this->container->getParameter('siteId'),
                        "uci" => $member->getUci(),
                        "includePersonalInformation" => 'Y',
                        "version" => '4.0'
                    );
                    $account = $client->__soapCall("lookUpAccount", array($params));
                    //update username member
                    if(array_key_exists('userName', $account)) {
                        $member->setName($account->userName);
                    }
                    $em->persist($member);
                    $em->flush();
                    //insert mail in user session 
                    $this->container->get('session')->set('userInfo', array('email'=>$account->email, 'nom'=>$account->lastName, 'prenom'=>$account->firstName));
                    return $this->redirect($this->generateUrl('adidas_om_profil', array('id' => $member->getId())));
                }
                else {
                    return $this->render('AdidasOmBundle:Default:index.html.twig',array('error'=>'veuillez s\'inscrire au jeu'));
                }
            }
        }
        return $this->render('AdidasOmBundle:Default:connection.html.twig',array('form_auth'=>$form_cnx->createView()));
    }

    public function confirmAction()
    {
        return $this->render('AdidasOmBundle:Om:confirm.html.twig', array('fb_share_url' => $this->container->getParameter('fb_share_url')));
    }
    
    public function presentationAction()
    {
        return $this->render('AdidasOmBundle:Om:presentation.html.twig');
    }
    
    public function thanksAction()
    {
        return $this->render('AdidasOmBundle:Om:thanks.html.twig');
    }
    
    public function profilAction(Request $request)
    {
       if(empty($this->container->get('session')->get('user'))) {
            throw new \LogicException('veuillez se connecter pour accéder à cette page');
        }
        $id = $request->get('id')?$request->get('id'):$this->container->get('session')->get('user');

        $cnx = $request->get('cnx');
        if(isset($id)) $this->container->get('session')->set('user',$id);
        if(!empty($cnx)) {
          $url =  $this->get('adidas_connection.oauth')->connectInstagram();
          return $this->redirect($url);
        }
        if($request->get('code')) {
            $picture = $this->get('adidas_connection.oauth')->updatePicture($request->get('code'));
           // $this->container->get('session')->set('picture',$picture);
        }
        $userInfo = $this->container->get('session')->get('userInfo');
        $branding = $this->get('site_context')->getCurrentBranding();
        $em = $this->getDoctrine()->getManager();
        $member =  $this->get('adidas.repository.challenge.member')  
                           ->getInfoMember($id);
        if(!empty($member)) {
            $member = $member[0];
            $users = $this->get('adidas.repository.challenge.member')
                ->getBestByClub();
            foreach ($users as $key=>$user) {
                if($user['id'] == $id) {
                    $points  = $user['s'];
                    $class = $key;
                }
            }
        }
        else {
            $member = $this->getDoctrine()
                           ->getRepository('AdidasOmBundle:Member')
                           ->findOneById($id);
        }
        if(empty($member)) {
            throw new \LogicException('pas d\'utilisateur enregistrer avec cet id');
        }
        if(!empty($picture)) {
            $member->setPicture($picture);
            $em->persist($member);
            $em->flush();
        }
      //  var_dump($member); die('tes');
        $countMembers = $this->get('adidas.repository.challenge.member')
                       ->getCountMemberByClub();
        $userChallenges = $this->get('adidas.repository.challenge.member')->getLastChallengesByUser($id);
         return $this->render('AdidasOmBundle:Om:profil.html.twig', array('member' => $member, 'count_member' => $countMembers, 
             'points' =>(!empty($points))?$points:0, 'class'=> (!empty($class))?$class:0, 'user_challenge' => $userChallenges
                 ));
    }
    
    public function editProfilAction(Request $request) 
    {
        $branding = $this->get('site_context')->getCurrentBranding();
        $id = $request->get('id')?$request->get('id'):$this->container->get('session')->get('user');
        if(empty($id)) {
            throw new \LogicException('veuillez se connecter pour accéder à cette page');
        }
        $member = $this->getDoctrine()->getRepository('AdidasOmBundle:Member')->findOneById($id);
        if (!$member) {
            throw $this->createNotFoundException('Unable to find member with this id: '.$id);
        }
        $form_reg= $this->createForm(new RegisterFormType());
        $form_reg->handleRequest($request);

        if( $form_reg->isValid() ){
            $error = null;
            $em = $this->getDoctrine()->getManager();
            $client = new \SoapClient($this->container->getParameter('soap_service_url'));
            $data = $form_reg->getData();
            $jersey_id = $data['code'];
            $params = array(
                "userToken"         => $this->container->getParameter('userToken'),
                "sessionToken"      => $this->container->get('session')->get('sessionToken'),
                "siteId"            => $this->container->getParameter('siteId'),
                "uci"               => $member->getUci(),
                "countryOfSite"     => "fr",
                "email"             => $data['email'],
                "password"          => $data['mdp'],
                "userName"          => $data['util'],
                "dateOfBirth"       => $data['aaaa'] ."-" . $data['mm'] ."-" . $data['jj'],
                "AMF"               => "N",
                "firstName"         => $data['nom'],
                "lastName"          => $data['prenom'],
                "gender"            => $data['civ'],
                "actionType"        => "UPDATEPROFILE",
                "version" => '4.0'
                );
            
            $firstName	= $data['prenom'];
            $lastName	= $data['nom'];
            $email 	= $data['email'];
            //Check Password
            if( !Tools::isValidPassword( $data['mdp']) ){
                $error = "Le mot de passe doit faire entre 8 et 12 caractéres et doit contenir au moins une majuscule, une minuscule et un chiffre";
            }

            if( !Tools::isValidNameText($data['nom']) || !Tools::isValidNameText($data['prenom']) ){
                $error = "Votre nom et votre prénom doit être constitué d'au moins 2 lettres";
            }
            //Check member age
            switch (Tools::getAgeAuthorization($data['jj'], $data['mm'], $data['aaaa'])){
                case "unauthorized"         :   die('Error minor');break;
                case "parent_authorization" :   if( $data['pcf_try'] ){
                                                    die();
                                                }
                                                break;
            }           
            //check valid Jersey
            if($jersey_id != NULL ){
                if( !Tools::isJerseyNumberValid($jersey_id)){
                        $error = "Ce numéro de maillot est invalide";
                    //    return $this->render('AdidasOmBundle:Default:editProfil.html.twig',array('form_reg'=>$form_reg->createView(),'error'=>$error,'member' => $member));
                }
            }
            
	    //Appel web service updateAccount
            $response = $client->__soapCall("UpdateAccount", array($params));
            $conditionCode =  $response->conditionCode;
            if($conditionCode != "iCCD_UPDT_ACCT_0001" )
            {
                $error = "Une erreure c'est produite".$conditionCode;
            } 
            else {
               $this->container->get('session')->set('userInfo', array('email'=>$email, 'nom'=>$lastName, 'prenom'=>$firstName));
               if(!empty($jersey_id) && empty($error)){
                    $member->setJerseyId($jersey_id);
                    $em->persist($member);
                    $em->flush();
                }
            }
            
            if ($request->isXmlHttpRequest()) {
                $resp = new Response(json_encode($error));
                $resp->headers->set('Content-Type', 'application/json');
                return $resp;
            }
            
        }
        return $this->render('AdidasOmBundle:Om:editProfil.html.twig', array('form_reg' => $form_reg->createView(),'member' => $member ));
       }
       
    public function premiumProfilAction(Request $request)
    {
        if($request->getMethod() == 'POST') {
            $error = null;
            $id = $request->get('id')?$request->get('id'):$this->container->get('session')->get('user');
            $jersey_id = $request->get('code');
              if( !Tools::isJerseyNumberValid($jersey_id)){
                  $error = "Ce numéro de maillot est invalide";
              }
              $jersey = $this->getDoctrine()->getRepository('AdidasOmBundle:Member')->findByJerseyId($jersey_id);
              if(count($jersey)){
                  $error = "Ce numéro de maillot à déja été utilisé";
              }
              
              $em = $this->getDoctrine()->getManager();
              $member= $em->getRepository('AdidasOmBundle:Member')->findOneById($id);
              $member->setJerseyId($jersey_id);
              $em->persist($member);
              $em->flush();
              if ($request->isXmlHttpRequest()) {
                $resp = new Response(json_encode($error));
                $resp->headers->set('Content-Type', 'application/json');
                return $resp;
             }
             if(empty($error)) {
                  return $this->redirect($this->generateUrl('adidas_om_profil', array('id' => $id)));
             }
             
       }
       return $this->render('AdidasOmBundle:Default:premium.html.twig'); 
   }
    
}

