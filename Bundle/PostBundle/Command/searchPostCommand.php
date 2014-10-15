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

class searchPostCommand  extends ContainerAwareCommand
{
    protected $container ;
    protected $router ;

    protected function configure()
    {
        $this
            ->setName('script:searchPost')
            ->setDescription('get all post by htag in challenge');
    }

    protected function execute(InputInterface $input, OutputInterface $output) 
    {
        
        try {
            $result = array();
            $log = $this->createLog();
            $this->container = $this->getApplication()->getKernel()->getContainer();
            $consumer_key = $this->container->getParameter('consumer_key_adidadev');
            $consumer_secret = $this->container->getParameter('consumer_secret_adidadev');
            $challenges =  $this->container->get('adidas.repository.challenge')->findAll();
            foreach ($challenges as $row) { 
               if($row->getTypeReseau() == 'twitter') {
                    $id_max_twitter = $this->container->get('adidas.repository.post')->getIdMaxTwitter($row->getId());
                    $this->connectTwitter($row->getHtag(), $consumer_key, $consumer_secret,$id_max_twitter,$row->getId(),$output);
                }
                if($row->getTypeReseau() == 'instagram') {
                    $id_max_instagram = $this->container->get('adidas.repository.post')->getIdMaxInstagram($row->getId());
                    $this->connectInstg($row->getHtag(), $id_max_instagram, $row->getId(),$output);
               }
            }
        } catch (\Exception $e) {
            die($e);
            $log = $this->createLog();
            $this->addInLogConsole($e, $output, $log);
        }
    }

    // create a log channel
    private function createLog() 
    {
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
    
    protected function connectTwitter($htag, $consumer_key, $consumer_secret, $idMaxTag, $idChallenge, $output) {
        
        if(isset($idMaxTag)) {
            $url = 'https://api.twitter.com/1.1/search/tweets.json?since_id='.$idMaxTag.'&q=%23'.$htag.'&ShowUser=true';    
        }
        else {
            $url = "https://api.twitter.com/1.1/search/tweets.json?q=%23".$htag."&ShowUser=true";
        }
        $tweets = $this->container->get('adidas_post.oauth')->ConnectTwitter($consumer_key, $consumer_secret,"146897321-6PDstCUqtfPlCzpEMAr8lc7VgiIPGEEEw7JzrNr7", "KbtelJbuIuWmFSPAvKzrRb2rGTGdDak2a9IR4hZ64Am03", $url, 'GET' );
        $max_id = $tweets->search_metadata->max_id_str;
        foreach ($tweets->statuses as $status) {
            $result = $this->container->get('adidas.repository.post')->InsertPostByTwitter($status->user->screen_name, $status->text,$status->created_at,$max_id,$idChallenge);     
            if($result){
                $output->writeln('insert post twitter avec id : '.$result);
            }
        }
    }
    public function connectInstg($hashtag, $idMaxTag, $idChallenge, $output)
    {
        $clientId = $this->container->getParameter('client_id_inst_adidadev');
        $query = array(
         'client_id' => $clientId,
        );
        $url = 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?'.http_build_query($query);
        if(isset($idMaxTag)) {
            $url = $url.'&max_tag_id='.$idMaxTag;    
        }
        try {
            $data = $this->container->get('adidas_post.oauth')->ConnectInstagram($url);
            $netx_tag_id  = $data['pagination']['next_max_tag_id'];
            foreach ($data['data'] as $tweet) {
               $date = date('Y-m-d H:i:s',$tweet['created_time']);
               $result =  $this->container->get('adidas.repository.post')->InsertPostByInstagram($tweet['caption'],$tweet['images']['low_resolution'],$date,$netx_tag_id,$idChallenge);     
               if($result){
                    $output->writeln('insert post instagram avec id : '.$result);
               }
            }
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
