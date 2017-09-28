<?php

namespace Mary\Common\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Mary\Common\Validator\NotEmpty;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Mary\WebBundle\Entity\User as UserEntity;

class UserType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $this->formBuilder
            ->add('username', TextType::class, [
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
            ->add('password', PasswordType::class, [
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
            ]);

        if (isset($options['attr']['id']) && $options['attr']['id'] == 'register') {
            $this->formBuilder->add('age', TextType::class, [
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

        $this->formBuilder->add('submit', SubmitType::class, ['attr' => ['formnovalidate' => 'formnovalidate']]);
        return $this;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserEntity::class
        ));
    }
}