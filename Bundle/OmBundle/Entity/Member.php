<?php

namespace Adidas\Bundle\OmBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Adidas\Bundle\OmBundle\Entity\MemberRepository")
 */
class Member
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uci", type="string", length=255)
     */
    private $uci;

    /**
     * @var string
     *
     * @ORM\Column(name="jersey_id", type="string", length=255)
     */
    private $jerseyId;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="club", type="string", length=255)
     */
    private $club;
   
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $active;
    
    /** 
     * @ORM\OneToMany(targetEntity="Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember", mappedBy="challengeMemberId") 
     */
    private $challengeMemberId;
    
    /**
     * @var string
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     */
    private $picture;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->challengeMemberId = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Member
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
     * Set uci
     *
     * @param string $uci
     * @return Member
     */
    public function setUci($uci)
    {
        $this->uci = $uci;

        return $this;
    }

    /**
     * Get uci
     *
     * @return string 
     */
    public function getUci()
    {
        return $this->uci;
    }

    /**
     * Set jerseyId
     *
     * @param string $jerseyId
     * @return Member
     */
    public function setJerseyId($jerseyId)
    {
        $this->jerseyId = $jerseyId;

        return $this;
    }

    /**
     * Get jerseyId
     *
     * @return string 
     */
    public function getJerseyId()
    {
        return $this->jerseyId;
    }
    
     /**
     * Set name
     *
     * @param string $name
     * @return Member
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set club
     *
     * @param string $club
     * @return Member
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
     * Set active
     *
     * @param boolean $active
     * @return Member
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add challengeMemberId
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember $challengeMemberId
     * @return Member
     */
    public function addChallengeMemberId(\Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember $challengeMemberId)
    {
        $this->challengeMemberId[] = $challengeMemberId;

        return $this;
    }

    /**
     * Remove challengeMemberId
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember $challengeMemberId
     */
    public function removeChallengeMemberId(\Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember $challengeMemberId)
    {
        $this->challengeMemberId->removeElement($challengeMemberId);
    }

    /**
     * Get challengeMemberId
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChallengeMemberId()
    {
        return $this->challengeMemberId;
    }
    
    public function __toString()
    {
        return $this->uci;
    }
}