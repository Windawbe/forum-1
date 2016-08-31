<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', ChoiceType::class, array(
            'choices'  => array(
                'Modérateur' => 'ROLE_MODERATOR', 
                'Admin' => 'ROLE_ADMIN', 
                'Super Admin' => 'ROLE_SUPER_ADMIN'),
            'multiple' => true,
            'expanded' => true
        ));
        
        $builder->remove('current_password', 'password');
    }
    
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}