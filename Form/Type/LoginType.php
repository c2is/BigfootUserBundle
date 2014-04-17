<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * LoginType class
 */
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('password', 'password')
            ->add(
                'remember_me',
                'checkbox',
                array(
                    'required' => false
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'intention' => 'authentication'
            )
        );
    }

    public function getName()
    {
        return 'bigfoot_login';
    }
}
