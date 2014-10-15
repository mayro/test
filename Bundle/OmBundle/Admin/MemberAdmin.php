<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Adidas\Bundle\OmBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Adidas\Bundle\OmBundle\Branding\SiteContext;

class MemberAdmin extends Admin
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
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('uci', null, array('disabled' => true))
            ->add('jerseyId', null, array('disabled' => true))
            ->add('club', null, array('disabled' => true))
            ->add('active', null, array('label' => 'force Premium', 'required' => false));

    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('club', null, array('label' => 'CLUB'));
    }
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('uci')
            ->add('jersey_id')
            ->add('club')
            ->add('_action', 'actions', array(
                'actions' => array(
                'edit' => array(),
                'delete' => array(),
                )
           ));

    }
    protected function configureRoutes(RouteCollection $collection)
    {
         $collection->clearExcept(array('list', 'edit'));
    }
    
}