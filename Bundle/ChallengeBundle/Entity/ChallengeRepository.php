<?php

namespace Adidas\Bundle\ChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Adidas\Bundle\OmBundle\Branding\SiteContext;

/**
 * ChallengeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChallengeRepository extends EntityRepository
{
    private $club;
    private $siteContext;

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

    public function getAll() 
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->setParameter('CLUB', $this->club);
        return $query->getQuery()->getResult();
    }
    public function getAvantChallenge() 
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->andWhere('m.avant = 1')
                    ->andWhere('m.active = 1')
                    ->setParameter('CLUB', $this->club)
                    ->orderBy('m.id','DESC')
                    ->setMaxResults(1);
        return $query->getQuery()->getResult();
        
    }
    public function getIdAvantChallenge()
    {
       $result =  $this->getAvantChallenge();
       if($result) {
           return $result[0]->getId();
       }
       else {
           return 0;
       }
       
    }
    public function getActiveChallenges()
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->andWhere('m.active = 1')
                    ->setParameter('CLUB', $this->club)
                    ->orderBy('m.id','DESC');
        return $query->getQuery()->getResult();       
    }
    
    public function getActiveClassementChallenges()
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->andWhere('m.active = 1')
                    ->andWhere('m.classement = 1')
                    ->setParameter('CLUB', $this->club)
                    ->orderBy('m.id','DESC');
        return $query->getQuery()->getResult();   
        
    }
    
    public function getChallenge($id)
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.id = :ID')
                    ->setParameter('ID', $id);
        return $query->getQuery()->getResult();       
    }
    
    public function getSourceChallenge($id)
    {
        $result = $this->getChallenge($id);
        return $result[0]->getTypeReseau();
    }
    
    public function getlastActiveChallenge()
    {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->andWhere('m.active = 1')
                    ->setParameter('CLUB', $this->club)
                    ->orderBy('m.id','DESC')
                    ->setMaxResults(1);
        return $query->getQuery()->getResult();
        
    }
    
}
