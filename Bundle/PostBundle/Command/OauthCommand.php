<?php
namespace Adidas\Bundle\PostBundle\Command;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthSignatureMethodHMACSHA1;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthConsumer;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthUtil;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthToken;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthRequest;

class OauthCommand {
    
    private $container ;
    
    
    public function _construct($container) {
        $this->container = $container;
    }
    
    
    public function ConnectTwitter($conumser_key, $consumer_secret, $acces_token, $acces_secret, $url, $method) {
        
        $consumer = new OAuthConsumer($conumser_key, $consumer_secret);
        $token = new OAuthConsumer($acces_token, $acces_secret);
        $sha1_method = new OAuthSignatureMethodHMACSHA1();
         try {
                $reqst = OAuthRequest::from_consumer_and_token($consumer, $token, $method, $url, array());
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
                curl_setopt($ci, CURLOPT_URL, $reqst->to_url());
                $responseFinal = curl_exec($ci);
                $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
                $http_info = array_merge($http_info, curl_getinfo($ci));
                curl_close ($ci);
                $tweets =  json_decode($responseFinal);
                return $tweets;
         }
         catch(Exception $e) {
            return $e->getMessage();
        }

        
    }
    
    public function  ConnectInstagram($url) {
        
        try {
            $curl_connection = curl_init($url);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($curl_connection);
            curl_close ($curl_connection);
            $data = json_decode($response, true);
            return $data;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
        
    }
    
    
}
