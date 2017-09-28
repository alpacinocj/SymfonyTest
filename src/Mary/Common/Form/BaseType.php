<?php

namespace Mary\Common\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class BaseType extends AbstractType
{
    /**
     * @var FormBuilder
     */
    protected $formBuilder = null;

    protected $options = [];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->formBuilder = $builder;
        $this->options = $options;
    }

}