<?php

namespace Adidas\Bundle\ChallengeBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengMember
 *
 * @ORM\Entity(repositoryClass="Adidas\Bundle\ChallengeBundle\Entity\ChallengeMemberRepository")
 * @ORM\Table(name="challengeMember")
 */
class ChallengeMember
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Adidas\Bundle\OmBundle\Entity\Member", inversedBy="challengeMemberId") 
     */
    private $idMember;

    /**
    * @ORM\ManyToOne(targetEntity="Challenge", inversedBy="challengeMemberId") 
    */
    private $idChallenge;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $points;

     /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $idSocial;
    
    /**
     * @var string
     * @ORM\Column(name= "id_last_post", type="string", length=255, nullable=true)
     */
    private $idLastPost;
    
    /**
     * @var string
     * @ORM\Column(name= "point_month", type="integer", nullable=true)
     */
    private $pointMonth;
    
    /**
     * @var string
     * @ORM\Column(name= "point_hour", type="integer", nullable=true)
     */
    private $pointHour;
    
    /**
     * @var date
     * @ORM\Column(type="date")
     */
    private $dateLastUpdate;
    
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $picture;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set picture
     *
     * @param string $picture
     * @return ChallengeMember
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return ChallengeMember
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set idSocial
     *
     * @param string $idSocial
     * @return ChallengeMember
     */
    public function setIdSocial($idSocial)
    {
        $this->idSocial = $idSocial;

        return $this;
    }

    /**
     * Get idSocial
     *
     * @return string 
     */
    public function getIdSocial()
    {
        return $this->idSocial;
    }

    /**
     * Set idLastPost
     *
     * @param string $idLastPost
     * @return ChallengeMember
     */
    public function setIdLastPost($idLastPost)
    {
        $this->idLastPost = $idLastPost;

        return $this;
    }

    /**
     * Get idLastPost
     *
     * @return string 
     */
    public function getIdLastPost()
    {
        return $this->idLastPost;
    }

    /**
     * Set pointMonth
     *
     * @param integer $pointMonth
     * @return ChallengeMember
     */
    public function setPointMonth($pointMonth)
    {
        $this->pointMonth = $pointMonth;

        return $this;
    }

    /**
     * Get pointMonth
     *
     * @return integer 
     */
    public function getPointMonth()
    {
        return $this->pointMonth;
    }

    /**
     * Set pointHour
     *
     * @param integer $pointHour
     * @return ChallengeMember
     */
    public function setPointHour($pointHour)
    {
        $this->pointHour = $pointHour;

        return $this;
    }

    /**
     * Get pointHour
     *
     * @return integer 
     */
    public function getPointHour()
    {
        return $this->pointHour;
    }

    /**
     * Set dateLastUpdate
     *
     * @param \DateTime $dateLastUpdate
     * @return ChallengeMember
     */
    public function setDateLastUpdate($dateLastUpdate)
    {
        $this->dateLastUpdate = $dateLastUpdate;

        return $this;
    }

    /**
     * Get dateLastUpdate
     *
     * @return \DateTime 
     */
    public function getDateLastUpdate()
    {
        return $this->dateLastUpdate;
    }

    /**
     * Set idMember
     *
     * @param \Adidas\Bundle\OmBundle\Entity\Member $idMember
     * @return ChallengeMember
     */
    public function setIdMember(\Adidas\Bundle\OmBundle\Entity\Member $idMember = null)
    {
        $this->idMember = $idMember;

        return $this;
    }

    /**
     * Get idMember
     *
     * @return \Adidas\Bundle\OmBundle\Entity\Member 
     */
    public function getIdMember()
    {
        return $this->idMember;
    }

    /**
     * Set idChallenge
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge
     * @return ChallengeMember
     */
    public function setIdChallenge(\Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge = null)
    {
        $this->idChallenge = $idChallenge;

        return $this;
    }

    /**
     * Get idChallenge
     *
     * @return \Adidas\Bundle\ChallengeBundle\Entity\Challenge 
     */
    public function getIdChallenge()
    {
        return $this->idChallenge;
    }
}
