<?php

/*
 * ovveride model manager 
 */

namespace Adidas\Bundle\OmBundle\Admin;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use Sonata\DoctrineORMAdminBundle\Admin\FieldDescription;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionInterface;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;

use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\DBALException;

use Symfony\Component\Form\Exception\PropertyAccessDeniedException;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Exporter\Source\DoctrineORMQuerySourceIterator;
use Adidas\Bundle\OmBundle\Branding\SiteContext;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as AdminModelManager;

class ModelManager extends AdminModelManager
{
    protected $registry;

    protected $cache = array();

    const ID_SEPARATOR = '~';

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
    /**
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($class)
    {
        return $this->getEntityManager($class)->getMetadataFactory()->getMetadataFor($class);
    }
    
    public function getEntityManager($class)
    {
        $branding = $this->siteContext->getCurrentBranding();
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (!isset($this->cache[$class])) {
           $em = $this->registry->getManager($branding['connecteur']);
            if (!$em) {
                throw new \RuntimeException(sprintf('No entity manager defined for class %s', $class));
            }

            $this->cache[$class] = $em;
        }

        return $this->cache[$class];
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
       
        try {
            $entityManager = $this->getEntityManager($object);
            
            $entityManager->persist($object);
            $entityManager->flush();
        } catch (\PDOException $e) {
            throw new ModelManagerException(sprintf('Failed to create object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
        } catch (DBALException $e) {
            throw new ModelManagerException(sprintf('Failed to create object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
        }
    }
}
