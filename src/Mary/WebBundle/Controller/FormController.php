<?php

namespace Mary\WebBundle\Controller;

use Mary\WebBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

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
            return $this->redirectToRoute('show_message', ['message' => 'Add user success !']);
        }

        return $this->render('MaryWebBundle:Form:create_user.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }
}