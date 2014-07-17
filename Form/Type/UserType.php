<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

use Bigfoot\Bundle\UserBundle\Event\UserEvent;

/**
 * User Type
 *
 * @package BigfootUserBundle
 */
class UserType extends AbstractType
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var array
     */
    private $languages;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext
     * @param array                    $languages
     * @param EventDispatcher          $eventDispatcher
     */
    public function __construct(SecurityContextInterface $securityContext, array $languages, EventDispatcher $eventDispatcher)
    {
        $this->securityContext = $securityContext;
        $this->languages       = $languages;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * BuildForm
     *
     * @param  FormBuilderInterface $builder
     * @param  array                $options
     * @return null
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $langs = array_keys($this->languages);
        $langChoices = array_combine($langs, $langs);

        $builder
            ->add('username')
            ->add(
                'email',
                'text',
                array(
                    'attr' => array(
                        'class' => 'width-100'
                    )
                )
            )
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
                    'enabled',
                    'checkbox',
                    array(
                        'required' => false
                    )
                )
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

        $this->eventDispatcher->dispatch(UserEvent::CREATE_FORM, new GenericEvent($builder));
    }

    /**
     * SetDefaultOptions
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Bigfoot\Bundle\UserBundle\Entity\User'
            )
        );
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_user';
    }
}
