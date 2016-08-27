<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
         $builder
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('roles', 'choice', array('choices' =>
                  array(
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                  ),
                  'required' => true,
                  'multiple' => false,
                ));
            ->add('save', SubmitType::class)
         ;    
        
        /*
        ->add('user')
            ->add('username', null, array('widget' => 'single_text'))
            ->add('password', null, array('widget' => 'single_text'))
            ->add('email', null, array('widget' => 'single_text'))
            ->add('roles', 'collection', array(
                   'type' => 'choice',
                   'options' => array(
                       'label' => false, 
                       'choices' => array(
                           'ROLE_ADMIN' => 'Admin',
                           'ROLE_USER' => 'Utilisateur'
                       )
                   )
               )
           )
            ->add('save', SubmitType::class)
        */
        /*$builder
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                ))
                ->add('roles', 'choice', array('label' => 'Attribution des droits :',
                    "expanded" => true,
                    "multiple" => true,
                    'choices' => array(
                        'ROLE_USER' => 'Utilisateurs lambda',
                        'ROLE_ADMIN' => 'Utilisateurs pouvant gérer le forum',
                    ),
                ));*/
        /*
         $permissions = array(
        'ROLE_USER'        => 'First role',
        'ROLE_ADMIN'       => 'Second role',
        'ROLE_SUPER_ADMIN' => 'Third role'
        );
        
        $builder
        ->add(
            'id',
            'entity',
            array(
                'class'    => 'MainBundle:Users',
                'property' => 'displayName',
                'label'    => 'Choose the user',
            )
        )
        ->add(
            'role',
            'choice',
            array(
                'label'   => 'Choose the role',
                'choices' => $permissions,
            )
        )
        ->add(
            'save',
            'submit'
        );
        */
    }
}
