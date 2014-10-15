<?php

namespace Adidas\Bundle\ChallengeBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * Challenge
 *
 * @ORM\Entity(repositoryClass="Adidas\Bundle\ChallengeBundle\Entity\ChallengeRepository")
 * @ORM\Table(name="challenge")
 */
class Challenge
{
     /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $nameChallenge;

    /**
     * @var string
     * @ORM\Column( type="text")
     */
    private $description;

     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $typeCompte;

    /**
     * @var boolean
     * @ORM\Column( type="boolean", length=255)
     */
    private $active;
    
    /**
     * @var boolean
     * @ORM\Column( type="boolean", length=255)
     */
    private $avant;

    /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $typeChallenge;

    /**
     * @var date
     * @ORM\Column( type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @var date
     * @ORM\Column( type="date", nullable=true)
     */
    private $dateFin;
     
     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $vignette;

     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $typeReseau;

     /**
     * @var string
     * @ORM\Column( type="text")
     */
    private $reglesChallenge;

     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $dotation;
    
    /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $imageAvant;

     /**
     * @ORM\Column( type="string", length=255)
     */
    private $htag;

     /**
     * @var string
     * @ORM\Column( type="string", length=255)
     */
    private $club;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pts;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $classement;
    
    
     /** 
     * @ORM\OneToMany(targetEntity="ChallengeMember", mappedBy="idChallenge") 
     */
    private $challengeMemberId;
    
    /** 
     * @ORM\OneToMany(targetEntity="ChallengePost", mappedBy="idChallenge") 
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
     * Set classement
     * @param string $classement
     * @return Challenge
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;

        return $this;
    }

    /**
     * Get classement
     *
     * @return string 
     */
    public function getClassement()
    {
        return $this->classement;
    }

    /**
     * Set pts
     *
     * @param string $pts
     * @return Challenge
     */
    public function setPts($pts)
    {
        $this->pts = $pts;

        return $this;
    }

    /**
     * Get pts
     *
     * @return string 
     */
    public function getPts()
    {
        return $this->pts;
    }
    
    /**
     * Set nameChallenge
     *
     * @param string $nameChallenge
     * @return Challenge
     */
    public function setNameChallenge($nameChallenge)
    {
        $this->nameChallenge = $nameChallenge;

        return $this;
    }

    /**
     * Get nameChallenge
     *
     * @return string 
     */
    public function getNameChallenge()
    {
        return $this->nameChallenge;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Challenge
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set typeCompte
     *
     * @param string $typeCompte
     * @return Challenge
     */
    public function setTypeCompte($typeCompte)
    {
        $this->typeCompte = $typeCompte;

        return $this;
    }

    /**
     * Get typeCompte
     *
     * @return string 
     */
    public function getTypeCompte()
    {
        return $this->typeCompte;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Challenge
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
     * Set imageAvant
     *
     * @param string imageAvant
     * @return Challenge
     */
    public function setImageAvant($imageAvant)
    {
        $this->imageAvant = $imageAvant;

        return $this;
    }

    /**
     * Get imageAvant
     *
     * @return string 
     */
    public function getImageAvant()
    {
        return $this->imageAvant;
    }
    
    /**
     * Set avant
     *
     * @param boolean $avant
     * @return Challenge
     */
    public function setAvant($avant)
    {
        $this->avant = $avant;

        return $this;
    }

    /**
     * Get avant
     *
     * @return boolean 
     */
    public function getAvant()
    {
        return $this->avant;
    }

    /**
     * Set typeChallenge
     *
     * @param string $typeChallenge
     * @return Challenge
     */
    public function setTypeChallenge($typeChallenge)
    {
        $this->typeChallenge = $typeChallenge;

        return $this;
    }

    /**
     * Get typeChallenge
     *
     * @return string 
     */
    public function getTypeChallenge()
    {
        return $this->typeChallenge;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Challenge
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Challenge
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set vignette
     *
     * @param string $vignette
     * @return Challenge
     */
    public function setVignette($vignette)
    {
        $this->vignette = $vignette;

        return $this;
    }

    /**
     * Get vignette
     *
     * @return string 
     */
    public function getVignette()
    {
        return $this->vignette;
    }

    /**
     * Set typeReseau
     *
     * @param string $typeReseau
     * @return Challenge
     */
    public function setTypeReseau($typeReseau)
    {
        $this->typeReseau = $typeReseau;

        return $this;
    }

    /**
     * Get typeReseau
     *
     * @return string 
     */
    public function getTypeReseau()
    {
        return $this->typeReseau;
    }

    /**
     * Set reglesChallenge
     *
     * @param string $reglesChallenge
     * @return Challenge
     */
    public function setReglesChallenge($reglesChallenge)
    {
        $this->reglesChallenge = $reglesChallenge;

        return $this;
    }

    /**
     * Get reglesChallenge
     *
     * @return string 
     */
    public function getReglesChallenge()
    {
        return $this->reglesChallenge;
    }

    /**
     * Set dotation
     *
     * @param string $dotation
     * @return Challenge
     */
    public function setDotation($dotation)
    {
        $this->dotation = $dotation;

        return $this;
    }

    /**
     * Get dotation
     *
     * @return string 
     */
    public function getDotation()
    {
        return $this->dotation;
    }

    /**
     * Set htag
     *
     * @param string $htag
     * @return Challenge
     */
    public function setHtag($htag)
    {
        $this->htag = $htag;

        return $this;
    }

    /**
     * Get htag
     *
     * @return string 
     */
    public function getHtag()
    {
        return $this->htag;
    }

    /**
     * Set club
     *
     * @param string $club
     * @return Challenge
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

    public function getAbsolutePath()
     {
         return null === $this->vignette ? null : $this->getUploadRootDir().'/'.$this->vignette;
     }

     public function getWebPath()
     {
        return null === $this->vignette ? null : $this->getUploadDir().'/'.$this->vignette;
     }

     protected function getUploadRootDir($basepath)
     {
        return $basepath.$this->getUploadDir();
     }

     protected function getUploadDir()
     {
        return 'uploads/images/challenges';
     }

     public function upload($basepath)
     {
        $this->vignette->move($this->getUploadRootDir($basepath), $this->vignette->getClientOriginalName());
        $this->dotation->move($this->getUploadRootDir($basepath), $this->dotation->getClientOriginalName());
        $this->imageAvant->move($this->getUploadRootDir($basepath), $this->imageAvant->getClientOriginalName());
        $this->setVignette($this->vignette->getClientOriginalName());
        $this->setDotation($this->dotation->getClientOriginalName());
        $this->setImageAvant($this->imageAvant->getClientOriginalName());
     }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->challengeMemberId = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add challengeMemberId
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\ChallengeMember $challengeMemberId
     * @return Challenge
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

    /**
     * Add challengePostId
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\ChallengePost $challengePostId
     * @return Challenge
     */
    public function addChallengePostId(\Adidas\Bundle\ChallengeBundle\Entity\ChallengePost $challengePostId)
    {
        $this->challengePostId[] = $challengePostId;

        return $this;
    }

    /**
     * Remove challengePostId
     *
     * @param \Adidas\Bundle\ChallengeBundle\Entity\ChallengePost $challengePostId
     */
    public function removeChallengePostId(\Adidas\Bundle\ChallengeBundle\Entity\ChallengePost $challengePostId)
    {
        $this->challengePostId->removeElement($challengePostId);
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
    
    public function __toString()
    {
        return $this->nameChallenge;
    }

    /**
     * Set idPost
     *
     * @param \Adidas\Bundle\PostBundle\Entity\Post $idPost
     * @return Challenge
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
    
    public function  getEtatChallenge()
    {   
        if($this->getDateFin()) {
            if($this->getDateFin()->format('d-m-Y') != date('d-m-Y') )
            {
                return 'Terminé';
            }
            else {
                return 'En cour';
            }
        }
    }
}
