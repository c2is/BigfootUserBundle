<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

use Bigfoot\Bundle\UserBundle\Form\DataTransformer\StringToUserTransformer;

class ForgotPasswordType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'email',
                array(
                    'invalid_message' => 'form.bigfoot_forgot_password.email.invalid',
                    'required'        => true,
                )
            );

        $builder
            ->get('email')
            ->addModelTransformer(new StringToUserTransformer($this->entityManager));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'Bigfoot\Bundle\UserBundle\Form\Model\ForgotPasswordModel',
                'csrf_protection' => false,
            )
        );
    }

    public function getName()
    {
        return 'bigfoot_forgot_password';
    }
}
