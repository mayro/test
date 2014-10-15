<?php
namespace Adidas\Bundle\ChallengeBundle\Command;
use Adidas\Bundle\PostBundle\Command\OAuth\TwitterOauth;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthSignatureMethodHMACSHA1;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthConsumer;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthUtil;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthToken;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ConnectionSocialCommand {
    
    protected $container ;

   /**
    * @param ContainerInterface $container
    */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function connectTwitter()
    {
        $consumer_key = $this->container->getParameter('consumer_key_adidadev_profil');
        $consumer_secret = $this->container->getParameter('consumer_secret_adidadev_profil');
        $oauth_callback = $this->container->getParameter('oauth_callback_profil');
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
    
    public function connectInstagram() 
    {
        $consumer_key = $this->container->getParameter('client_id_inst_adidadev_profil');
        $consumer_secret_key = $this->container->getParameter('client_secret_inst_adidadev_profil');
        $callback = $this->container->getParameter('oauth_callback_inst_adidadev_profil');
        $url = "https://api.instagram.com/oauth/authorize/?client_id=".$consumer_key."&amp;redirect_uri=".$callback."&amp;response_type=code";
        return $url;
    }
    public function updatePicture($code) 
    {
        $url = "https://api.instagram.com/oauth/access_token";
        $access_token_parameters = array(
                'client_id'                =>     $this->container->getParameter('client_id_inst_adidadev_profil'),
                'client_secret'            =>     $this->container->getParameter('client_secret_inst_adidadev_profil'),
                'grant_type'               =>     'authorization_code',
                'redirect_uri'             =>     $this->container->getParameter('oauth_callback_inst_adidadev_profil'),
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
        if(!empty($resp)) {
            return $resp['user']['profile_picture'];
        }
        else {
            return false;
        }

    }
    
    /**
    * Get a service from the container
    *
    * @param string The service to get
    */
    protected function get($service)
    {
        return $this->container->get($service);
    }
    
}
