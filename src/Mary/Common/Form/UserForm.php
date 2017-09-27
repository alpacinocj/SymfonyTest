<?php

namespace Mary\Common\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Mary\Common\Validator\NotEmpty;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\FormBuilder;

class UserForm extends BaseForm
{
    public function __construct(ContainerInterface $container, $entity)
    {
        parent::__construct($container, $entity);
    }

    /**
     * @return FormBuilder
     */
    public function getFormBuilder()
    {
        return $this->createFormBuilder($this->entity)
            ->add('username', 'text', [
                'constraints' => [
                    new NotEmpty([
                        'message' => '用户名不能为空'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 10,
                        'minMessage' => '最少{{ limit }}个字符',
                        'maxMessage' => '最多{{ limit }}个字符',
                    ])
                ]
            ])
            ->add('password', 'password', [
                'constraints' => [
                    new NotEmpty([
                        'message' => '密码不能为空'
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 20,
                        'minMessage' => '最少{{ limit }}个字符',
                        'maxMessage' => '最多{{ limit }}个字符',
                    ]),
                ]
            ])
            ->add('age', 'text', [
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 120,
                        'minMessage' => '不能小于{{ limit }}',
                        'maxMessage' => '不能大于{{ limit }}',
                    ]),
                ]
            ]);
    }
}