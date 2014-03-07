<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserType extends AbstractType
{
    private $securityContext;

    private $languages;

    public function __construct(SecurityContextInterface $securityContext, array $languages)
    {
        $this->securityContext  = $securityContext;
        $this->languages        = $languages;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $langs = array_keys($this->languages);
        $langChoices = array_combine($langs, $langs);

        $builder
            ->add('username')
            ->add('email')
            ->add('fullName')
            ->add(
                'locale',
                'choice',
                array(
                    'choices' => $langChoices
                )
            );

        if ($this->securityContext->isGranted('ROLE_ADMIN')) {
            $builder
                ->add(
                    'formRoles',
                    'entity',
                    array(
                        'class'    => 'BigfootUserBundle:Role',
                        'multiple' => true,
                    )
                );
        }

        $builder
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
                'data_class' => 'Bigfoot\Bundle\UserBundle\Entity\User'
            )
        );
    }

    public function getName()
    {
        return 'admin_user';
    }
}
