<?php

namespace Adidas\Bundle\PostBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 * 
 * @ORM\Entity(repositoryClass="Adidas\Bundle\PostBundle\Entity\PostRepository")
 * @ORM\Table(name="Post")
 */
class Post
{
     /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $nameMember;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @var boolean
     * @ORM\Column( type="boolean", length=255)
     */
    private $active;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
     /**
     * @var string
     * @ORM\Column(name="idMax", type="string", length=255)
     */
     private $id_max;
     
     /** 
     * @ORM\OneToMany(targetEntity="Adidas\Bundle\ChallengeBundle\Entity\ChallengePost", mappedBy="idPost") 
     */
    private $challengePostId;
    
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
     * Set comment
     *
     * @param string $comment
     * @return Post
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Post
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Post
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
     * Set image
     *
     * @param string $image
     * @return Post
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set nameMember
     *
     * @param string $nameMember
     * @return Post
     */
    public function setNameMember($nameMember)
    {
        $this->nameMember = $nameMember;

        return $this;
    }

    /**
     * Get nameMember
     *
     * @return string 
     */
    public function getNameMember()
    {
        return $this->nameMember;
    }

    /**
     * Set id_max
     *
     * @param string $idMax
     * @return Post
     */
    public function setIdMax($idMax)
    {
        $this->id_max = $idMax;

        return $this;
    }

    /**
     * Get id_max
     *
     * @return string 
     */
    public function getIdMax()
    {
        return $this->id_max;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->challengePostId = new \Doctrine\Common\Collections\ArrayCollection();
    }

 

    /**
     * Get challengePostId
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChallengePostId()
    {
        return $this->challengePostId;
    }

    /**
     * Add idChallenge
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge
     * @return Post
     */
    public function addIdChallenge(\Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge)
    {
        $this->idChallenge[] = $idChallenge;

        return $this;
    }

    /**
     * Remove idChallenge
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge
     */
    public function removeIdChallenge(\Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge)
    {
        $this->idChallenge->removeElement($idChallenge);
    }

    /**
     * Get idChallenge
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdChallenge()
    {
        return $this->idChallenge;
    }
}
