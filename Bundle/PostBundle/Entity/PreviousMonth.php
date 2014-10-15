<?php

namespace Adidas\Bundle\PostBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 * 
 * @ORM\Entity(repositoryClass="Adidas\Bundle\PostBundle\Entity\PreviousMonthRepository")
 * @ORM\Table(name="PreviousMonth")
 */
class PreviousMonth
{
     /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idChallenge;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userMax;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userMin;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MinPoints;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $MaxPoints;
    
    /**
     * @var string
     * @ORM\Column(name="club", type="string", length=255)
     */
    private $club;
    
    /**
     *  @var date
     * @ORM\Column(type="date")
     */
    
    private  $updateAt;

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
     * Set idChallenge
     *
     * @param string $idChallenge
     * @return PreviousMonth
     */
    public function setIdChallenge($idChallenge)
    {
        $this->idChallenge = $idChallenge;

        return $this;
    }

    /**
     * Get idChallenge
     *
     * @return string 
     */
    public function getIdChallenge()
    {
        return $this->idChallenge;
    }

    /**
     * Set userMax
     *
     * @param string $userMax
     * @return PreviousMonth
     */
    public function setUserMax($userMax)
    {
        $this->userMax = $userMax;

        return $this;
    }

    /**
     * Get userMax
     *
     * @return string 
     */
    public function getUserMax()
    {
        return $this->userMax;
    }

    /**
     * Set userMin
     *
     * @param string $userMin
     * @return PreviousMonth
     */
    public function setUserMin($userMin)
    {
        $this->userMin = $userMin;

        return $this;
    }

    /**
     * Get userMin
     *
     * @return string 
     */
    public function getUserMin()
    {
        return $this->userMin;
    }

    /**
     * Set MinPoints
     *
     * @param string $minPoints
     * @return PreviousMonth
     */
    public function setMinPoints($minPoints)
    {
        $this->MinPoints = $minPoints;

        return $this;
    }

    /**
     * Get MinPoints
     *
     * @return string 
     */
    public function getMinPoints()
    {
        return $this->MinPoints;
    }

    /**
     * Set MaxPoints
     *
     * @param string $maxPoints
     * @return PreviousMonth
     */
    public function setMaxPoints($maxPoints)
    {
        $this->MaxPoints = $maxPoints;

        return $this;
    }

    /**
     * Get MaxPoints
     *
     * @return string 
     */
    public function getMaxPoints()
    {
        return $this->MaxPoints;
    }

    /**
     * Set club
     *
     * @param string $club
     * @return PreviousMonth
     */
    public function setClub($club)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return string 
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set updateAt
     *
     * @param \DateTime $updateAt
     * @return PreviousMonth
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime 
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }
}
