<?php

namespace Adidas\Bundle\OmBundle\Entity;
use Adidas\Bundle\OmBundle\Branding\SiteContext;
use Doctrine\ORM\EntityRepository;

class MemberRepository extends EntityRepository
{
    private $club;
    private $siteContext;

    public function setSiteContext(SiteContext $siteContext) {
        $this->siteContext = $siteContext;
        $branding = $siteContext->getCurrentBranding();

        $this->club = $branding['club'];
    }

    public function changeEntityManager($em) {
        $this->_em = $em;
        return $this;
    }

    public function getAll() {
        $query = $this->createQueryBuilder('m')
                    ->where('m.club = :CLUB')
                    ->setParameter('CLUB', $this->club);

        return $query->getQuery()->getResult();
    }
}
