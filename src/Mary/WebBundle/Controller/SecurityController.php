<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\GenericEvent;
use Mary\WebBundle\Event\Events;
use Mary\WebBundle\Entity\User;
use Mary\Common\Form\UserType;

class SecurityController extends BaseController
{
    public function registerAction(Request $request)
    {
        $userEntity = new User();

        $userForm = $this->createForm(UserType::class, $userEntity, ['attr' => ['id' => 'register']]);

        // handle form
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // 加密密码
            $password = $this->get('security.password_encoder')->encodePassword($userEntity, $userEntity->getPassword());
            $userEntity->setPassword($password);

            $em = $this->getEntityManager();
            $em->persist($userEntity);
            $em->flush();

            // 事件派遣
            $event = new GenericEvent($userEntity, ['extra' => 'user register']);
            $this->getDispatcher()->dispatch(Events::USER_REGISTER_EVENT, $event);

            return $this->redirectToRoute('show_message', [
                'message' => 'Add user success !'
            ]);
        }

        return $this->render('MaryWebBundle:Security:register.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('MaryWebBundle:Security:login.html.twig', [
            'error' => $error,
            'lastUsername' => $lastUsername
        ]);
    }
}