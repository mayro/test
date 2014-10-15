<?php
namespace Adidas\Bundle\ChallengeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Adidas\Bundle\OmBundle\Branding\SiteContext;

/**
 * ChallengeMemberRepository
 *
 */

class ChallengeMemberRepository extends EntityRepository
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
    
    public function updatePoints($id, $points,$idLastId,$pointHour) 
    {
        $q =  $this->createQueryBuilder('s')
            ->update('AdidasChallengeBundle:ChallengeMember', 's')
            ->set('s.points', $points)
            ->set('s.idLastPost', $idLastId)
            ->set('s.pointHour', $pointHour)
            ->set('s.pointMonth', $points)
            ->set('s.dateLastUpdate', date('d-m-Y'))
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery()
            ->execute();
       return $q;
    }
    
    public function getMaxByMonth($club, $idChallenge=NULL) 
    {
        $req = $this->createQueryBuilder('m')
            ->select('MAX(m.pointMonth) as M , m')
            ->join('m.idMember', 'c');
            if($idChallenge) {
                $req->where('m.idChallenge = ?1')
                    ->Andwhere('c.club = ?2')
                    ->setParameter(1, $idChallenge)
                    ->setParameter(2, $club);
            }
            else {
                $req->where('c.club = ?1')
                    ->setParameter(1, $club);
            }
            $max = $req->setMaxResults(1)
            ->getQuery()->getResult();
            return $max;
    }
    
    public function getMinByMonth($club, $idChallenge=NULL) 
    {
        $req = $this->createQueryBuilder('m')
            ->select('MIN(m.pointMonth) as M , m');
            if($idChallenge) {
                $req->where('m.idChallenge = ?1')
                    ->setParameter(1, $idChallenge);
            } 
        $min = $req->setMaxResults(1)
            ->getQuery()->getResult();
        return $min;
    }
    
    public function countParticipantChallenge($id)
    {
        $query = $this->createQueryBuilder('m')
                    ->select('c.id')
                    ->join('m.idMember','c')
                    ->Where('m.idChallenge = :ID')
                    ->setParameter('ID', $id);
       return count($query->getQuery()->getResult());
    }
    
    //inserer un membre apres la premiere participation via twitter ou instagram
    public function InsertNewParticipMember($reponse, $source, $points , $idChallenge,$id)
    {
        $cnx = $this->getEntityManager()->getConnection();
        if($source == 'twitter') {
            $stm = $cnx->prepare("INSERT INTO challengemember (idMember_id, idSocial, points, idChallenge_id, id_last_post, picture) values('".$id."','".$reponse->user->id."','".$points."','".$idChallenge."','".$reponse->id_str."','".$reponse->user->profile_image_url_https."')")
                   ->execute();
            if($stm) {
                $url = $reponse->user->profile_image_url_https;
                $stm1 = $cnx->prepare("UPDATE  member SET picture = '$url'  WHERE id = $id")
                   ->execute();
        }
        }
        else {
            $stm = $cnx->prepare("INSERT INTO challengemember (idMember_id, idSocial, idChallenge_id, picture) values('".$id."','".$reponse['user']['id']."','".$idChallenge."','".$reponse['user']['profile_picture']."')")
                   ->execute();
            if($stm) {
                $url = $reponse['user']['profile_picture'];
                $stm1 = $cnx->prepare("UPDATE  member SET picture = '$url'  WHERE id = $id")
                   ->execute();
            }
        }

        return $stm;
    }
    
    
    public function InsertNewParticipVide($id_member, $points, $idChallenge)
    {
        $cnx = $this->getEntityManager()->getConnection();
        $stm = $cnx->prepare("INSERT INTO challengemember (idMember_id, points, idChallenge_id) values('".$id_member."','".$points."','".$idChallenge."')")
                   ->execute();
        if($stm) return true;
        else return false;
        
    }
    
    public function getCountMemberByClub($id=NULL)
    {
        $query = $this->createQueryBuilder('m')
                    ->leftJoin('m.idMember','c')
                    ->Where('c.club = :CLUB');
                    if($id) {
                        $query->andWhere('m.idChallenge = :ID')
                        ->setParameter('ID', $id);
                    }
                    $query ->groupBy('m.idMember')
                    ->setParameter('CLUB', $this->club);
        return count($query->getQuery()->getResult());
    }
    
    public function getBestTenByClub($id=null, $max=1)
    {
        $query = $this->createQueryBuilder('m')
                ->select('SUM(m.points) as s , c.picture as picture, c.name as name, c.id as id')
                ->leftJoin('m.idMember','c')
                ->Where('c.club = :CLUB');
                if($id) {
                        $query->andWhere('m.idChallenge = :ID')
                        ->setParameter('ID', $id);
                    }
                $query->groupBy('m.idMember')
                ->orderBy('s','DESC')
                ->setMaxResults($max)
                ->setParameter('CLUB', $this->club);       
        return $query->getQuery()->getResult();
    }
    
    public function getLastMemberByClub($id=null)
    {
        $query = $this->createQueryBuilder('m')
                ->select('SUM(m.points) as s , c.picture as picture, c.name as name, c.id as id, c.jerseyId as jerseyId, c.active as active')
                ->leftJoin('m.idMember','c')
                ->Where('c.club = :CLUB');
                if($id) {
                    $query->andWhere('m.idChallenge = :ID')
                    ->setParameter('ID', $id);
                }
                $query->groupBy('m.idMember')
                ->orderBy('s','ASC')
                ->setMaxResults(1)
                ->setParameter('CLUB', $this->club);       
        return $query->getQuery()->getResult();  
    }
    
    public function getInfoMember($id)
    {
        $query = $this->createQueryBuilder('m')
                ->select('c.picture as picture, c.name as name, c.id as id, c.jerseyId as jerseyId, c.active as active')
                ->leftJoin('m.idMember','c')
                ->Where('c.club = :CLUB')
                ->andWhere('m.idMember = :ID')
                ->groupBy('m.idMember')
                ->setMaxResults(1)
                ->setParameter('CLUB', $this->club)
                ->setParameter('ID', $id);  
        return $query->getQuery()->getResult();
    }
    
    public function getBestByClub($id=null)
    {
        $query = $this->createQueryBuilder('m')
                ->select('SUM(m.points) as s , c.picture as picture, c.name as name, c.id as id, c.jerseyId as jerseyId, c.active as active')
                ->leftJoin('m.idMember','c')
                ->Where('c.club = :CLUB');
                if($id) {
                    $query->andWhere('m.idChallenge = :ID')
                    ->setParameter('ID', $id);
                }
                $query->groupBy('m.idMember')
                ->orderBy('s','DESC')
                ->setParameter('CLUB', $this->club);       
        return $query->getQuery()->getResult();
    }
    
    public function getLastChallengesByUser($id)
    {
        $query = $this->createQueryBuilder('m')
                ->select('SUM(m.points) as s , c.nameChallenge as challenge, c.typeChallenge as type, c.id as id')
                ->Join('m.idChallenge','c')
                ->Where('c.club = :CLUB')
                ->andWhere('m.idMember = :ID')
                ->groupBy('m.idChallenge')
                ->setParameter('CLUB', $this->club)
                ->setParameter('ID', $id);  
        return $query->getQuery()->getResult();
        
    }
    
    public function getChallengeMember($id_challenge, $id_member) {
        $query = $this->createQueryBuilder('m')
                ->Where('m.idMember = :member')
                ->andWhere('m.idChallenge = :challenge')
                ->setParameter('member', $id_member)
                ->setParameter('challenge', $id_challenge);
        return $query->getQuery()->getResult();
    }
}
