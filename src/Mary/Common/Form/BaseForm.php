<?php

namespace Mary\Common\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;

abstract class BaseForm
{
    protected $container;
    protected $entity;

    public function __construct(ContainerInterface $container, $entity)
    {
        $this->container = $container;
        $this->entity = $entity;
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return FormBuilder
     */
    public function createFormBuilder($data = null, array $options = array())
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            $type = 'Symfony\Component\Form\Extension\Core\Type\FormType';
        } else {
            // not using the class name is deprecated since Symfony 2.8 and
            // is only used for backwards compatibility with older versions
            // of the Form component
            $type = 'form';
        }

        return $this->container->get('form.factory')->createBuilder($type, $data, $options);
    }

    abstract function getFormBuilder();
}