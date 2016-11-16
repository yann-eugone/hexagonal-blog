<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Domain\Blog\Exception\Author\AuthorNotFoundException;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * @Route("/author/{id}", name="author")
     * @Method({"GET"})
     *
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        try {
            $author = $this->getRepository()->getById($id);
        } catch (AuthorNotFoundException $exception) {
            throw $this->createNotFoundException(null, $exception);
        }

        $countPost = $this->getPostCounterRepository()->countForAuthor($author);
        $countComment = $this->getCommentCounterRepository()->countForAuthor($author);

        $posts = $this->getPostRepository()->search(['author' => $author]);

        return $this->render(
            'blog/author/show.html.twig',
            [
                'author' => $author,
                'posts' => $posts,
                'count' => [
                    'post' => $countPost,
                    'comment' => $countComment,
                ],
            ]
        );
    }

    /**
     * @return AuthorRepository
     */
    private function getRepository()
    {
        return $this->get('repository.author');
    }

    /**
     * @return PostRepository
     */
    private function getPostRepository()
    {
        return $this->get('repository.post');
    }

    /**
     * @return PostCounterRepository
     */
    private function getPostCounterRepository()
    {
        return $this->get('repository.post'); //todo use alias
    }

    /**
     * @return CommentCounterRepository
     */
    private function getCommentCounterRepository()
    {
        return $this->get('repository.comment'); //todo use alias
    }
}
