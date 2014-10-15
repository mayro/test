<?php

namespace Adidas\Bundle\ChallengeBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * ChallengMember
 *
 * @ORM\Entity()
 * @ORM\Table(name="challengePost")
 */
class ChallengePost
{
    /**
    * @ORM\Id
    * @ORM\Column(name="id", type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Adidas\Bundle\PostBundle\Entity\Post", inversedBy="challengePostId") 
     */
    private $idPost;

    /**
    * @ORM\ManyToOne(targetEntity="Challenge", inversedBy="challen.
     * gePostId") 
    */
    private $idChallenge;

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
     * Set idPost
     *
     * @param \Adidas\Bundle\PostBundle\Entity\Post $idPost
     * @return ChallengePost
     */
    public function setIdPost(\Adidas\Bundle\PostBundle\Entity\Post $idPost = null)
    {
        $this->idPost = $idPost;

        return $this;
    }

    /**
     * Get idPost
     *
     * @return \Adidas\Bundle\PostBundle\Entity\Post 
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * Set idChallenge
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\Challenge $idChallenge
     * @return ChallengePost
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
    
    public function __toString()
    {
        return $this->idChallenge->getNameChallenge();
    }
}
