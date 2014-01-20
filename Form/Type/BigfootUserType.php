<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BigfootUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('full_name')
            ->add(
                'locale',
                'choice',
                array(
                    'choices' => array(
                        'fr' => 'fr',
                        'en' => 'en'
                    )
                )
            )
            ->add('roles')
            ->add(
                'plainPassword',
                'repeated',
                array(
                    'type'     => 'password',
                    'required' => false,
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Bigfoot\Bundle\UserBundle\Entity\BigfootUser'
            )
        );
    }

    public function getName()
    {
        return 'bigfoot_user';
    }
}
