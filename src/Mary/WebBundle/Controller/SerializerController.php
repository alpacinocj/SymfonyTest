<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Mary\WebBundle\Entity\User;

class SerializerController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Serializer:index.html.twig', []);
    }

    // 对象序列化
    public function serializeAction(Request $request, $format)
    {
        $user = $this->getUserRepository()->findOneBy(['username' => 'jack']);
        $formatStr = $this->getSerializerService()->serialize($user, $format);
        echo $formatStr; die;
    }

    // 对象转成数组
    public function normalizeAction()
    {
        $user = $this->getUserRepository()->findOneBy(['username' => 'jack']);
        $userArr = $this->getSerializerService()->normalize($user);
        var_export($userArr); die;
    }
}