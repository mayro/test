<?php
namespace Adidas\Bundle\PostBundle\Command\OAuth;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthSignatureMethodHMACSHA1;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthConsumer;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthUtil;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthToken;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthRequest;

class TwitterOauth {
    
    /* Set up the API root URL. */
    public $host = "https://api.twitter.com/1.1/";
    /* Set timeout default. */
    public $timeout = 30;
    /* Set connect timeout. */
    public $connecttimeout = 30;
    /* Verify SSL Cert. */
    public $ssl_verifypeer = FALSE;
    /* Respons format. */
    public $format = 'json';
    /* user agent */
    public $agent = 'TwitterOAuth v0.2.0-beta2';
    /* the last HTTP status code returned. */
    public $http_code;
    public $sha1_method;
    public $consumer;
    public $token;
    public $method = 'GET';




    /**
    * construct TwitterOAuth object
    */
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
        $this->sha1_method = new OAuthSignatureMethodHMACSHA1();
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        if (!empty($oauth_token) && !empty($oauth_token_secret)) {
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
        } 
        else {
            $this->token = NULL;
        }
    }
    /**
    * Format and sign an OAuth / API request
    */
    function signRequestOauth($url,$parameters) {
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $this->method, $url, $parameters);
        $request->sign_request($this->sha1_method, $this->consumer, $this->token);
        return $request;
    }
    
    /**
    * GET curl
    */
    function getCurl($request) {
        $http_info = array();
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_USERAGENT, $this->agent);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);;
        curl_setopt($ci, CURLOPT_URL, $request->to_url());
        $response = curl_exec($ci);
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        $http_info = array_merge($http_info, curl_getinfo($ci));
        curl_close($ci);
        return $response;
   }
   
   function getHeader($ch, $header) {
    $http_header = array();
    $i = strpos($header, ':');                                           
    if (!empty($i)) {
        $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
        $value = trim(substr($header, $i + 2));
        $http_header[$key] = $value;
    }
    return $http_header;
    }
    
    function authorizeURL() { 
        return 'https://api.twitter.com/oauth/authorize';
    }
    function getUrlRequestToken() {
        return 'https://api.twitter.com/oauth/request_token';
    }    
    function  getUrlAccessToken() {
        return 'https://api.twitter.com/oauth/access_token';
    }
}

    