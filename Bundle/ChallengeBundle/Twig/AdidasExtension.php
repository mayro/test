<?php
namespace Adidas\Bundle\ChallengeBundle\Twig;
use Symfony\Component\Locale\Locale;
use Adidas\Bundle\OmBundle\Branding\SiteContext;

class AdidasExtension extends \Twig_Extension
{
    protected $container ;
    protected $em ;
    private $club;
    private $siteContext;

    public function __construct($container)
    {
        try {
            $this->container = $container;
        } 
        catch (Symfony\Component\DependencyInjection\Exception\InactiveScopeException $e) {
        }
    }

    public function setSiteContext(SiteContext $siteContext) 
    {
        $this->siteContext = $siteContext;
        $branding = $siteContext->getCurrentBranding();
        $this->club = $branding['club'];
    }
    
    public function changeEntityManager($em) 
    {
        $this->_em = $em;
        return $this;
    }
    public function getName()
    {
        return 'adidas_extension';
    }

    public function getFunctions()
    {
        return array(
          'getCountMemberByChallenge' => new \Twig_Function_Method($this, 'getCountMemberByChallenge'),
          'timeLeft' => new \Twig_Function_Method($this, 'timeLeft', array('is_safe' => array('html'))),
          'getPosition' => new \Twig_Function_Method($this, 'getPosition'),
          'getPartcipChallenge' => new \Twig_Function_Method($this, 'getPartcipChallenge'),
        );
    }

    public function getCountMemberByChallenge($id)
    {
        $branding = $this->siteContext->getCurrentBranding();
        $count= $this->container->get('doctrine')->getManager($branding['connecteur'])->getRepository('AdidasChallengeBundle:ChallengeMember')->countParticipantChallenge($id);
        return $count ;
    }
    public function getPartcipChallenge($id_challenge,$id_member)
    {
        $branding = $this->siteContext->getCurrentBranding();
        $result = $this->container->get('doctrine')->getManager($branding['connecteur'])
                   ->getRepository('AdidasChallengeBundle:ChallengeMember')->getChallengeMember($id_challenge, $id_member);
        return $result;
        
    }


    public function diff($start, $end = false)
    {
        if (!$end) { $end = time(); }
        if (!is_numeric($start) || !is_numeric($end)) { return false; }

        // Convert $start and $end into EN format (ISO 8601)
        $dStart = date_create(date('Y-m-d H:i:s', $start));
        $dEnd = date_create(date('Y-m-d H:i:s', $end));
        $diff = $dStart->diff($dEnd);

        return array(
            'years'    => $diff->format('%y'),
            'months'   => $diff->format('%m'),
            'days'     => $diff->format('%d'),
            'hours'    => $diff->format('%h'),
            'minutes'  => $diff->format('%i'),
            'secondes' => $diff->format('%s')
        );
    }

    /**
     *
     * Decorate key of a given array with $string
     * @param $array
     */
    protected function decorateKeys(Array $array, $decorator = '%')
    {
        $output = array();
        foreach ($array as $key => $value) {
            $output[$decorator . $key . $decorator] = $value;
        }
        return $output;
    }

    /**
     *
     * Prepare the output to be used in translate twig tag
     * @param $theDate
     */
    public function timeLeft($theDate)
    {
        $diff = $this->diff(strtotime(date_format($theDate, 'Y-m-d H:i:s')), time());
        if (!$diff['minutes'] && $diff['secondes']) {
            $diff['minutes'] = 1;
        }
        if ($diff['days']) {
            $count = ($diff['days'] == 1)?1:2;
        } elseif ($diff['days'] && $diff['hours']) {
            $count = ($diff['days'] == 1 && $diff['hours'] == 1)?1:(($diff['days'] == 1 && $diff['hours'] > 1)?2:3);
        } elseif ($diff['hours'] && $diff['minutes']) {
            $count = ($diff['hours'] == 1 && $diff['minutes'] == 1)?1:(($diff['hours'] == 1 && $diff['minutes'] > 1)?2:3);
        } elseif ($diff['hours']) {
            $count = ($diff['hours'] == 1)?1:2;
        } elseif ($diff['minutes']) {
            $count = ($diff['minutes'] == 1)?1:2;
        }
        if($diff['minutes'] && $diff['hours'] ) {
            $date = $diff['hours']." heures ". $diff['minutes']. " minutes";
        }
        elseif($diff['minutes']){
            $date = $diff['minutes']." minutes";
        }
        else {
            $date = $diff['hours']." heures";
        }
        

        return $date;
    }
    
    public function getPosition($ch,$res)
    {
        return stripos($ch, $res);
    }


}
