<?php

namespace Bigfoot\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class BigfootRoleMenuType extends AbstractType
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $itemsTemp = array();
        $items = array();

        // $menuFactory = $this->container->get("bigfoot.menu_factory");
        // foreach ($menuFactory->getMenus() as $menu) {
        //     $itemsTemp = array_merge($itemsTemp,$menu->getItems());
        // }
        // foreach ($itemsTemp as $item) {
        //     $items[$item->getName()] = $item;
        // }

        $builder
            ->add('slugs', 'choice', array("choices" => $items, "multiple" => true))
            ->add('role', 'text', array("read_only" => true));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bigfoot\Bundle\UserBundle\Entity\BigfootRoleMenu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bigfootrolemenutype';
    }
}
