<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:User:index.html.twig', []);
    }

    public function listAction(Request $request)
    {
        $keywords = $request->query->get('kw', '');
        $queryBuilder = $this->getUserRepository()->createQueryBuilder('u');
        if (!empty($keywords)) {
            $queryBuilder->where('u.username LIKE :username OR u.email LIKE :email OR u.age LIKE :age')
                ->setParameter('username', "%{$keywords}%")
                ->setParameter('email', "%{$keywords}%")
                ->setParameter('age', "%{$keywords}%");
        }
        $paginator = $this->getPaginatorService();
        $limit = 1;
        $pagination = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), $limit);
        return $this->render('MaryWebBundle:User:list.html.twig', [
            'keywords' => $keywords,
            'pagination' => $pagination
        ]);
    }
}