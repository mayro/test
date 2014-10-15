<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Adidas\Bundle\ChallengeBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ChallengeAdmin extends Admin
{
    /**
    * Default Datagrid values
    *
    * @var array
    */
   protected $datagridValues = array (
           '_page' => 1, 
           '_sort_order' => 'DESC',
           '_sort_by' => 'active' 
   );
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nameChallenge', null, array('required' => true))
            ->add('description', null, array('required' => true))
            ->add('reglesChallenge', null, array('required' => true))    
            ->add('typeCompte', 'choice', array('choices' => array('premium' => 'premium','fremium' => 'fremium')))
            ->add('typeChallenge', 'choice', array('choices' => array('évènementiel'=> 'évènementiel','permanent'=> 'permanent')))
            ->add('typeReseau', 'choice', array('choices' => array('twitter' =>'twitter', 'instagram' =>'instagram', 'vide' =>'vide')) )
            ->add('vignette', null, array('required' => true))
            ->add('dotation',null, array('required' => true))
            ->add('imageAvant',null, array('required' => true))
            ->add('htag', null, array('required' => true))
            ->add('dateDebut', null, array('required' => false))
            ->add('dateFin', null, array('required' => false))
            ->add('active', null, array('required' => false))
            ->add('avant', null, array('required' => false))
            ->add('classement', null, array('required' => false))
            ->add('pts', null, array('label'=> 'Nbr points','required' => true))
            ->add('club', 'choice', array('choices' => array('om' => 'om', 'ol' => 'ol')));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nameChallenge')
            ->add('typeChallenge')
            ->add('etat', 'string', array('label' => 'Etat', 'template' => 'AdidasChallengeBundle:Admin:etatChallenge.html.twig'))
            ->add('dateDebut')
            ->add('dateFin')
            ->add('avant', null, array('label' => 'Challenge en avant'))
            ->add('active')
            ->add('_action', 'actions', array(
                'actions' => array(
                'edit' => array(),
                'delete' => array(),
                )
           ));
    }
    
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('avant', null, array('label' => 'Challenge en avant'));
    }


}