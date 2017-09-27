<?php

namespace Mary\WebBundle\Controller;

use Mary\Common\Response\ResponseFormatter;
use Mary\WebBundle\MaryWebBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param string $name Entity Name
     * @return EntityManager
     */
    public function getEntityManager($name = null)
    {
        return $this->getDoctrine()->getEntityManager($name);
    }

    /**
     * @return EntityRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('MaryWebBundle:User');
    }

    /**
     * @return EntityRepository
     */
    public function getAuthorRepository()
    {
        return $this->getEntityManager()->getRepository('MaryWebBundle:Author');
    }

    /**
     * @return EntityRepository
     */
    public function getBookRepository()
    {
        return $this->getEntityManager()->getRepository('MaryWebBundle:Book');
    }

    public function responseJson(array $data, $status = Response::HTTP_OK, $headers = [])
    {
        $jsonResponse = new JsonResponse($data, $status, $headers);
        return $jsonResponse->send();
    }

    public function responseJsonSuccess($data, $headers = [])
    {
        $formatter = new ResponseFormatter();
        $successData = $formatter->success($data);
        return $this->responseJson($successData, Response::HTTP_OK, $headers);
    }

    public function responseJsonError($error, $code = 0, $headers = [])
    {
        $formatter = new ResponseFormatter();
        $errorData = $formatter->error($error, $code);
        return $this->responseJson($errorData, Response::HTTP_INTERNAL_SERVER_ERROR, $headers);
    }

    /**
     * Translate message
     * @param string $id 语言包文件中定义的KEY (xxx/translations/xxx.yml)
     * @param array $params 额外参数
     * @param string $domain 域 (默认有messages, admin, navigation, 默认取值为messages)
     * @param string $locale 语言场景
     * @return string
     */
    public function trans($id, $params = [], $domain = null, $locale = null)
    {
        return $this->container->get('translator')->trans($id, $params, $domain, $locale);
    }

}