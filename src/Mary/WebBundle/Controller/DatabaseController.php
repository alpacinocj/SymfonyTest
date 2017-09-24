<?php

namespace Mary\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Mary\WebBundle\Entity\Author;
use Mary\WebBundle\Entity\Book;
use Mary\WebBundle\Entity\User;

class DatabaseController extends BaseController
{
    public function indexAction()
    {
        return $this->render('MaryWebBundle:Database:index.html.twig');
    }

    public function addUserAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = new User();
        $user->setUsername('jack');
        $user->setAge(21);
        $user->setPassword('123456');
        $em->persist($user);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'add user success'
        ]);
    }

    public function addAuthorAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $author = new Author();
        $author->setName('Tomas');
        $author->setBirth('1956-07-21');
        $em->persist($author);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'add author success'
        ]);
    }

    public function addBookAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $author = $em->getRepository('MaryWebBundle:Author')->findOneBy(['id' => 1]);
        if (empty($author)) {
            echo 'Author does not existed'; exit;
        }
        $book = new Book();
        $book->setTitle('Jane Eyre');
        $book->setPrice(120.88);
        $book->setAuthor($author);
        $em->persist($book);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'add book success'
        ]);
    }

    public function getAuthorBooksAction(Request $request, $authorId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        /**
         * @var $author \Mary\WebBundle\Entity\Author
         */
        $author = $em->getRepository('MaryWebBundle:Author')->findOneBy(['id' => $authorId]);
        if (empty($author)) {
            echo 'Author does not existed'; exit;
        }
        $message = 'The author has some books : ';
        foreach ($author->getBooks() as $book) {
            $message .= $book->getTitle() . ' || ';
        }
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => $message
        ]);
    }

    public function updateUserAgeAction(Request $request, $userId, $age)
    {
        $em = $this->getDoctrine()->getEntityManager();
        /**
         * @var $user \Mary\WebBundle\Entity\User
         */
        $user = $em->getRepository('MaryWebBundle:User')->findOneBy(['id' => $userId]);
        if (empty($user)) {
            echo 'User does not existed'; exit;
        }
        $user->setAge($age);
        $em->persist($user);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'update user success'
        ]);
    }

    public function removeUserAction(Request $request, $userId)
    {
        $em = $this->getDoctrine()->getEntityManager();
        /**
         * @var $user \Mary\WebBundle\Entity\User
         */
        $user = $em->getRepository('MaryWebBundle:User')->findOneBy(['id' => $userId]);
        if (empty($user)) {
            echo 'User does not existed'; exit;
        }
        $em->remove($user);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'remove user success'
        ]);
    }
}