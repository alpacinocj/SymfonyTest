<?php

namespace Mary\WebBundle\Controller;

use Doctrine\Common\Util\Debug;
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
        $em = $this->getEntityManager();
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
        $em = $this->getEntityManager();
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
        $em = $this->getEntityManager();
        $author = $this->getAuthorRepository()->findOneBy(['id' => 1]);
        if (empty($author)) {
            echo 'Author does not existed'; exit;
        }
        $prices = range(20, 100);
        $book = new Book();
        $book->setTitle('Jane Eyre');
        $book->setPrice($prices[array_rand($prices)]);
        $book->setAuthor($author);
        $em->persist($book);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'add book success'
        ]);
    }

    public function showAuthorBooksAction(Request $request, $authorId)
    {
        $author = $this->getAuthorRepository()->findOneBy(['id' => $authorId]);
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
        $em = $this->getEntityManager();
        $user = $this->getUserRepository()->findOneBy(['id' => $userId]);
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
        $em = $this->getEntityManager();
        $user = $this->getUserRepository()->findOneBy(['id' => $userId]);
        if (empty($user)) {
            echo 'User does not existed'; exit;
        }
        $em->remove($user);
        $em->flush();
        return $this->render('MaryWebBundle:Database:index.html.twig', [
            'message' => 'remove user success'
        ]);
    }

    public function findUserByRawSqlAction(Request $request, $userId)
    {
        $conn = $this->get('database_connection');
        $user = $conn->fetchAssoc("select * from `user` where id = {$userId} limit 1");
        Debug::dump($user);
        exit;
    }
}