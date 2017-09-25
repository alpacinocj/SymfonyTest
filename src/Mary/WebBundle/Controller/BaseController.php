<?php

namespace Mary\WebBundle\Controller;

use Mary\WebBundle\MaryWebBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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
}