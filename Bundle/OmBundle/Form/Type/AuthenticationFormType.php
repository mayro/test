<?php
// src/Acme/DemoBundle/Form/Type/FriendMessageFormType.php
namespace Adidas\Bundle\OmBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthenticationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('utilb', 'text', array('label'=>'mail'))
                ->add('mdpb', 'password', array('label'=>'password'))
                ->add('optinb','checkbox',array('label'=>'Je souhaite être tenu informé des nouveautés d\'Adidas'))
                ->add('reglementb','checkbox',array('label'=>'J\'ai bien pris connaissance du reglement'))
                ->add('codeb', 'text', array('label'=>'code','required'=>false,'attr' => array('placeholder' => 'H-123456')));
    }

    public function getName()
    {
        return '';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('csrf_protection' => false));
    }
}