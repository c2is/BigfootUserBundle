<?php

namespace Bigfoot\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Util\SecureRandom;

use Bigfoot\Bundle\UserBundle\Entity\BigfootUser;

class BigfootUserType extends AbstractType
{
    private $encoderFactory;

    public function __construct(EncoderFactory $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $this->encoderFactory;

        $builder
            ->add('username')
            ->add('email')
            ->add('full_name')
            ->add('salt', 'hidden')
            ->add('password', 'password', array(
                'required' => false,
            ))
            ->add('userRoles')
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($factory) {
                $data = $event->getData();
                $form = $event->getForm();

                if (!$data) {
                    return null;
                }

                if ($data['password']) {
                    $user = new BigfootUser();
                    $generator = new SecureRandom();
                    $salt = str_replace(array('{', '}'), '-', utf8_encode($generator->nextBytes(128)));

                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($data['password'], $salt);

                    $data['password'] = $password;
                    $data['salt'] = $salt;
                }

                $event->setData($data);
            });
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bigfoot\Bundle\UserBundle\Entity\BigfootUser'
        ));
    }

    public function getName()
    {
        return 'bigfoot_user';
    }
}
