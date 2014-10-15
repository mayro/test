<?php
namespace Adidas\Bundle\PostBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthSignatureMethodHMACSHA1;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthConsumer;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthUtil;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthToken;
use Adidas\Bundle\PostBundle\Command\OAuth\OAuthRequest;
use Adidas\Bundle\ChallengeBundle\AdidasChallengeBundle;
use Adidas\Bundle\ChallengeMemberBundle\Entity\ChallengeMember;
use Adidas\Bundle\ChallengeMemberBundle\Entity\Challenge;

class TwitterCommand  extends ContainerAwareCommand
{
    protected $container ;
    protected $router ;

    protected function configure(){
        $this
            ->setName('script:twitterPost')
            ->setDescription('get post by id social user');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            $log = $this->createLog();
            $this->container = $this->getApplication()->getKernel()->getContainer();
            $consumer_key = $this->container->getParameter('consumer_key_adidadev');
            $consumer_secret = $this->container->getParameter('consumer_secret_adidadev');
            $oauth_callback = $this->container->getParameter('oauth_callback');
            $result = array();
            //calcul points global min et max par mois
            if($this->verifPreviousMonth()){
                $this->InsertIntoPreviousMonth();
            }
            $challengeMember =  $this->container->get('adidas.repository.challenge.member')->findAll();
            foreach ($challengeMember as $row) {
               $typeReseau = $row->getIdChallenge()->getTypeChallenge();
               if($typeReseau == 'twitter'){
                    $result = $this->connectTwitter($row->getIdSocial(),$row->getIdLastPost(), $consumer_key, $consumer_secret, $row->getIdChallenge()->getHtag());
               }
               elseif($typeReseau == 'instagram') {
                   $result = $this->connectInstg($row->getIdSocial(),$row->getIdLastPost(),$row->getIdChallenge()->getHtag());
               }
               if($this->verifPreviousMonth($row->getDateLastUpdate())){
                    $this->InsertIntoPreviousMonth($row->getIdChallenge()->getId());
               }
               if(!empty($result)) {
                    $reponse = $this->container->get('adidas.repository.challenge.member')->updatePoints($row->getId(), $result['points']+$row->getPoints(), $result['id_last_post'], $result['points']) ;
                    $output->writeln("update point pour challenge : ".$row->getIdChallenge()->getId() ." et ".$row->getIdSocial());
               }
               else {
                   die('result vide');
               }
               
               
            }            
        } 
        catch (\Exception $e) {
            die($e->getMessage());
           // $log = $this->createLog();
            //$this->addInLogConsole($e, $output, $log);
        }
    }

    // create a log channel
    private function createLog() {
        $log = new Logger('app/logs/scripttwitter');
        $log->pushHandler(new StreamHandler('app/logs/twitter.log', Logger::WARNING));
        return $log ;
    }

    private function addInLogConsole($e, $output, $log)
    {
        $output->writeln($e);
         // add records to the log
        $log->addAlert($e);
    }
    
    protected function connectTwitter($id, $id_last_post, $consumer_key, $consumer_secret, $htag) {
        $sha1_method = new OAuthSignatureMethodHMACSHA1();
        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        $token = new OAuthConsumer("146897321-6PDstCUqtfPlCzpEMAr8lc7VgiIPGEEEw7JzrNr7", "KbtelJbuIuWmFSPAvKzrRb2rGTGdDak2a9IR4hZ64Am03");

        $url = "https://api.twitter.com/1.1/statuses/user_timeline.json?user_id=".$id;
        if($id_last_post){
            $url = $url."&since_id=".$id_last_post;
        }
        try {
                $reqst = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url, array());
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
                $points = 0;
                $tweets =  json_decode($responseFinal);
                if(is_array($tweets) && count($tweets)>0) {
                    if(!(count($tweets) == 1 && 
                    $tweets[0]->id_str == $id_last_post)
                    ) {
                        foreach ($tweets as $twt) {
                            if(preg_match_all('/'.$htag.'/', $twt->text))
                            {
                            $points = $points + $this->container->getParameter('points'); ;
                            }
                        }
                    return array('points' => $points,
                        'id_last_post' => $tweets[0]->id_str);
                    }       
                } 
             return array('points' => $points,
                 'id_last_post' => $id_last_post);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
 }
    public function connectInstg($id, $id_last_post, $tag)
    {
        $query = array(
         'max_id' => $id_last_post,
         'client_id' => '54f76bd156ce4d56b4f774b788a1d36c',
        );
        $url = 'https://api.instagram.com/v1/users/'.$id.'/media/recent?'.http_build_query($query);
        try {
            $curl_connection = curl_init($url);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            //Data are stored in $data
            $data = json_decode(curl_exec($curl_connection), true);
            curl_close($curl_connection);
            $points = 0;
            if(is_array($data) && count($data)>0)
            {
                $id_last_post = $data['pagination']['next_max_id'];
                foreach ($data['data'] as $tweet) {
                    if(array_search($tag, $tweet['tags'])){
                        $points = $points + 1;
                    }
                }
            }
            return array('points' => $points, 'id_last_post' => $id_last_post);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function verifPreviousMonth($lastUpdate=NULL)
    {   
        if(!$lastUpdate) {
            $lastUpdate = $this->container->get('adidas.repository.previous.month')->getUpdateGlobal();
        }
        if(date('01-m-Y') === date('d-m-Y') && $lastUpdate != date('01-m-Y')){
            return true;
        }
        else{
            return false;
        }
    }
    // insert classement if $idchallenge alors insert bychallenge  else insert global
    public function InsertIntoPreviousMonth($idchallenge=NULL) {
        $club = array('om', 'ol');
        foreach($club as $clb)
        { 
            $min = $this->container->get('adidas.repository.challenge.member')->getMinByMonth($clb, $idchallenge);
            $max = $this->container->get('adidas.repository.challenge.member')->getMaxByMonth($clb, $idchallenge);
            $this->container->get('adidas.repository.previous.month')->InsertMaxMinBy($min, $max, $idchallenge);
        }
    }
}
