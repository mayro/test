<?php
namespace Adidas\Bundle\OmBundle\Listener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class MyListener {
    private $siteContext;

    public function __construct($siteContext) {
        $this->siteContext = $siteContext;

    }
    
    public function onKernelRequest(GetResponseEvent $event)
    {
        $host = $event->getRequest()->getHost();
        $branding = $this->siteContext->getBranding($host);
        $this->siteContext->setCurrentBranding($branding);
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $branding = $this->siteContext->getCurrentBranding();

        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $entity->setClub($branding['club']);
    }

    public function prePersist(LifecycleEventArgs $args) {
        $branding = $this->siteContext->getCurrentBranding();
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $entity->setClub($branding['club']);
    }
}