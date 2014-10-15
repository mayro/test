<?php

namespace Adidas\Bundle\PostBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PostAdmin extends Admin
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
            ->add('nameMember', null, array('disabled' => true))
            ->add('comment', null, array('disabled' => true))
            ->add('source', null, array('disabled' => true))    
            ->add('active', null, array('required' => false))
            ->add('image', null, array('disabled' => true))
            ->add('createdAt', null, array('disabled' => true));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nameMember')
            ->add('comment')
            ->add('source')    
            ->add('active')
            ->add('challengePostId', 'entity', array('class' => 'Adidas\Bundle\ChallengeBundle\Entity\ChallengePost', 'label' =>'Challenge'))
            ->add('image', NULL, array('template' => 'AdidasPostBundle:Admin:imagePost.html.twig', 'disabled' => true))
            ->add('createdAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                'edit' => array(),
                'delete' => array(),
                )
           ));
    }
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        #$datagridMapper->add( 'challengePostId',null, array('label' => 'Challenge'));
    }

    
    protected function configureRoutes(RouteCollection $collection)
    {
         $collection->clearExcept(array('list', 'edit'));
    }
    /*
    public function getChallenges() {
        $resps = $this->getModelManager()->createQuery('Adidas\Bundle\ChallengeBundle\Entity\Challenge','c')
                ->select('c.nameChallenge')->getQuery()->getResult();
       /* foreach ( $resps as $resp ) {
            $choices [$resp->getId()] = $resp->getUsername();
        }
        return $resps;
    }*/
    
}