<?php

namespace Mary\Common\Form;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                        'message' => 'The username can not empty'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 10,
                        'minMessage' => 'Minimum {{ limit }} characters',
                        'maxMessage' => 'Maximun {{ limit }} characters',
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotEmpty([
                        'message' => 'The password can not empty'
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 20,
                        'minMessage' => 'Minimum {{ limit }} characters',
                        'maxMessage' => 'Maximum {{ limit }} characters',
                    ]),
                ]
            ]);

        if (isset($options['attr']['id']) && $options['attr']['id'] == 'register') {
            $this->formBuilder->add('age', TextType::class, [
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 120,
                        'minMessage' => 'No less than {{ limit }}',
                        'maxMessage' => 'No more than {{ limit }}',
                    ]),
                ]
            ]);
        }

        $this->formBuilder->add('submit', SubmitType::class, ['attr' => ['formnovalidate' => 'formnovalidate']]);
        return $this;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserEntity::class
        ));
    }

}