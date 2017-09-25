<?php

namespace Mary\WebBundle\Controller;

use Mary\WebBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Mary\Common\Validator\NotEmpty;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class FormController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Form:index.html.twig');
    }

    public function createUserAction(Request $request)
    {
        $user = new User();
        $userForm = $this->createFormBuilder($user)
            ->add('username', 'text', ['label' => '用户名 :'])
            ->add('password', 'password', ['label' => '密码 :'])
            ->add('age', 'text', ['label' => '年龄 :'])
            ->add('submit', 'submit')
            ->getForm();

        // handle form
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('show_message', [
                'message' => 'Add user success !'
            ]);
        }

        return $this->render('MaryWebBundle:Form:create_user.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    public function createUserValidateAction(Request $request)
    {
        $user = new User();

        $userForm = $this->createFormBuilder($user)
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
            ])
            ->add('submit', 'submit', ['attr' => ['formnovalidate' => 'formnovalidate']])   // 关闭HTML5验证
            ->getForm();

        // handle form
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('show_message', [
                'message' => 'Add user success !'
            ]);
        }

        return $this->render('MaryWebBundle:Form:create_user.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}