<?php

namespace Mary\WebBundle\Controller;

use Mary\Common\Response\ResponseFormatter;
use Mary\WebBundle\MaryWebBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\VarDumper\VarDumper;

class BaseController extends Controller
{
    public function getParameter($name)
    {
        return parent::getParameter($name);
    }

    public function getKernelDebug()
    {
        return $this->getParameter('kernel.debug');
    }

    public function getRootDir()
    {
        return $this->getParameter('kernel.root_dir');
    }

    public function getLogsDir()
    {
        return $this->getRootDir() . '/logs';
    }

    public function getEnvironment()
    {
        return $this->getParameter('kernel.environment');
    }

    public function getLanguage()
    {
        return $this->getParameter('locale');
    }

    public function getLimitPerPage()
    {
        return $this->getParameter('limit_per_page');
    }

    public function getLoggerService()
    {
        return $this->container->get('logger');
    }

    public function getCurlService()
    {
        return $this->container->get('mary.webbundle.curl');
    }

    public function getMailerService()
    {
        return $this->container->get('swiftmailer.mailer');
    }

    public function getSerializerService()
    {
        return $this->container->get('serializer');
    }

    public function getPaginatorService()
    {
        return $this->container->get('knp_paginator');
    }

    public function getDispatcher()
    {
        return $this->container->get('event_dispatcher');
    }

    public function getAuthorizationChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    public function getCurrentUser()
    {
        return $this->getUser();
    }

    /*
     * @return boolean
     * */
    public function isLogin()
    {
        return $this->getAuthorizationChecker()->isGranted('IS_AUTHENTICATED_FULLY');
    }

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

    public function createBadRequestException($message = 'Bad Request', \Exception $previous = null)
    {
        return new BadRequestHttpException($message, $previous);
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

    public function dump($var, $exit = true)
    {
        VarDumper::dump($var);
        if ($exit === true) {
            exit;
        }
    }

}