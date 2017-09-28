<?php

namespace Mary\WebBundle\Controller;

use Mary\Common\Form\UserForm;
use Mary\Common\Form\UserType;
use Mary\WebBundle\Entity\User;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Util\Debug;

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

    public function registerAction(Request $request)
    {
        $userEntity = new User();

        $userForm = $this->createForm(UserType::class, $userEntity, ['attr' => ['id' => 'register']]);

        // handle form
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em = $this->getEntityManager();
            $em->persist($userEntity);
            $em->flush();
            return $this->redirectToRoute('show_message', [
                'message' => 'Add user success !'
            ]);
        }

        return $this->render('MaryWebBundle:Form:register.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    public function loginAction(Request $request)
    {
        $userEntity = new User();

        $userForm = $this->createForm(UserType::class, $userEntity, ['attr' => ['id' => 'login']]);

        // handle form
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $this->getUserRepository()->findOneByName($userEntity->getUsername());
            if (empty($user) || !$user->validatePassword($userEntity->getPassword())) {
                return $this->redirectToRoute('show_message', [
                    'message' => '用户名或者密码错误'
                ]);
            }
            return $this->redirectToRoute('show_message', [
                'message' => '登录成功'
            ]);
        }

        return $this->render('MaryWebBundle:Form:login.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}