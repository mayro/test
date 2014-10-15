<?php

namespace Adidas\Bundle\OmBundle\Classes;


class Tools
{

    static function isJerseyNumberValid ( $jerseyNumber ){
        if( preg_match("/^A|H|T\-[0-9]{6}$/", $jerseyNumber ) ){
            $arr = explode('-',$jerseyNumber);
            if ( intval($arr[1]) <= 326692)
                return true;
        }
        return false;
    }

    static function getAgeAuthorization($day, $mounth, $year){
        $time = mktime(0, 0, 0, $mounth, $day, $year );
        $timeImp = mktime(0, 0, 0, date("m")  , date("d"), date("Y")-13);
        $timeAuth = mktime(0, 0, 0, date("m")  , date("d"), date("Y")-16);
        if($time > 0)
        {
            if($time > $timeImp)
            {
                return 'unauthorized';
            }
                //Declancher une erreure
            if( ($time < $timeImp) && ($time > $timeAuth) ){
                return 'parent_authorization';
            }
            return 'authorized';
        }
        return 'authorized';
    }

    static function isValidPassword ($password)
    {
        if( preg_match("/^[a-zA-Z0-9]{8,12}$/", $password ) ){
               return(true);
        }else{
            return(false);
        }
    }

    static function isUsername ($password){
        if( preg_match("/^[a-zA-Z0-9]{8,12}$/", $password ) ){
            return(true);
        }else{
            return(false);
        }
    }

    static function isValidNameText($text){
        $regex = "(?=(.*[a-z]|[A-Z]){2,})";
        if( preg_match("/" . $regex . "/", $text ) ){
            return(true);
        }else{
            return(false);
        }
    }

    static function checkRegConditionCode($code){

        $error = false;
        switch($code)
        {
            case 'iCCD_CRT_ACCT_0001' : $error = false; break;
            case 'iCCD_CRT_ACCT_0003' : $error = "Cette adresse email existe déja";break;
            case 'iCCD_CRT_ACCT_0005' : $error = "Le mot de passe doit faire entre 8 et 12 caractéres et doit contenir au moins une majuscule, une minuscule et un chiffre";break;
        }
        return $error;
    }

    static function checkAuthConditionCode($code){

        $error = false;
        switch($code)
        {
            case 'iCCD_AUTH_CHK_0001' : $error = false; break;
            case 'iCCD_AUTH_CHK_0002' : $error = "Verifier votre nom d'utilisateur et votre mot de passe";break;
            case 'iCCD_AUTH_CHK_0003' : $error = "Verifier votre nom d'utilisateur et votre mot de passe";break;
            case 'iCCD_AUTH_CHK_0004' : $error = false; break;
            case 'iCCD_AUTH_CHK_0005' : $error = "Ce compte a été bloqué";break;
            case 'iCCD_AUTH_CHK_0006' : $error = "Vous ne pouvez pas participer à ce jeux, car vous n'avez pas l'age requis";break;
            case 'iCCD_AUTH_CHK_0007' : $error = false; break;
            case 'iCCD_AUTH_CHK_0008' : $error = "Cette adresse email existe déja";break;
            case 'iCCD_AUTH_CHK_0009' : $error = "Le mot de passe n'est pas correct";break;
            case 'iCCD_AUTH_CHK_0011' : $error = "Vous ne pouvez pas participer à ce jeux, car vous n'avez pas l'age requis";break;
            case 'iCCD_AUTH_CHK_0012' : $error = "Vous ne pouvez pas participer à ce jeux, car vous n'avez pas l'age requis";break;
        }
        return $error;
    }
    
    static function sendWelcomeEmail($email, $firstname, $lastname, $soap_url, $soap_url_jsp)
    {
    	$data = '	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:nms:rtEvent">
					<soapenv:Header/>
					<soapenv:Body>
						<urn:PushEvent>
						<urn:sessiontoken>mc/</urn:sessiontoken>
						<urn:domEvent>
						<rtEvent type="adiOM_Welcome_Email_FR" email="' . $email . '" origin="adiOM_Welcome_Email_FR" wishedChannel="0" externalId="" language="DE" country="DE" emailFormat="2">
							<ctx>
								<firstName>' . $firstname . '</firstName>
								<lastName>' . $lastname . '</lastName>
							</ctx>
						</rtEvent>
						</urn:domEvent>
						</urn:PushEvent>
						</soapenv:Body>
					</soapenv:Envelope>';
		$client = new \SoapClient($soap_url);
		$rep = $client->__doRequest($data, $soap_url_jsp, 'nms:rtEvent#PushEvent', 1);
    }
    


}