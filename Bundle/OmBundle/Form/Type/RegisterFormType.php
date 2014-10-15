<?php
// src/Acme/DemoBundle/Form/Type/FriendMessageFormType.php
namespace Adidas\Bundle\OmBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = array();
        for($i=2014;$i>=1900;$i--)
        {
            $years[$i]=$i;
        }

        $days = array();
        for($i=1;$i<=31;$i++)
        {
            if($i < 10)
                $days['0'.$i] = '0' . $i;
            else
                $days[$i]=$i;
        }

        $months = array();
        for($i=1;$i<=12;$i++)
        {
            if($i < 10)
                $months['0'.$i] = '0' . $i;
            else
                $months[$i]=$i;
        }

        $builder->add('civ', 'choice', array(   'label'=>'civ',
                                                'choices' => array('M' => 'M.', 'F' => 'Mme'),
                                                'expanded'=>true))
                ->add('nom', 'text', array('label'=>'nom'))
                ->add('prenom', 'text', array('label'=>'prenom'))
                ->add('email', 'text', array('label'=>'email'))
                ->add('cp', 'text', array('label'=>'cp'))
                ->add('jj', 'choice', array('label'=>false,'choices' => $days))
                ->add('mm', 'choice', array('label'=>false,'choices' => $months))
                ->add('aaaa', 'choice', array('label'=>false,'choices' => $years))
                ->add('util', 'text', array('label'=>'Nom d\'utilisateur'))
                ->add('mdp', 'password', array('label'=>'Mot de passe'))
                ->add('confirm_mdp', 'password', array('label'=>'Confirmez le mots de passe'))
                ->add('optin','checkbox',array('label'=>'Je souhaite être tenu informé des nouveautés d\'Adidas','required'=>false))
                ->add('reglement','checkbox',array('label'=>'J\'ai bien pris connaissance du reglement'))
                ->add('pcf_try','checkbox',array('label'=>'papa et maman sont bien au courant','required'=>false))
                ->add('code', 'text', array('label'=>'code','required'=>false,'attr' => array('placeholder' => 'H-123456')));

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