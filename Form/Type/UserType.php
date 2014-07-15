<?php

namespace Bigfoot\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Bigfoot\Bundle\CoreBundle\Manager\SettingsManager;

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
     * @var SettingsManager
     */
    private $settings;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext
     * @param array                    $languages
     * @param SettingsManager          $settings
     */
    public function __construct(SecurityContextInterface $securityContext, array $languages, SettingsManager $settings)
    {
        $this->securityContext = $securityContext;
        $this->languages       = $languages;
        $this->settings        = $settings;
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

        if ($this->settings->getSetting('user_send_email')) {
            $builder
                ->add(
                    'send_email',
                    'checkbox',
                    array(
                        'label'    => 'bigfoot_user.settings.label.send_email',
                        'required' => false,
                        'mapped'   => false
                    )
                );
        }
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
