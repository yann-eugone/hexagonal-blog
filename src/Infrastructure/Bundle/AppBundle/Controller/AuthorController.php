<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Domain\Blog\Exception\Author\AuthorNotFoundException;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\AuthorCommentCounterRepository;
use Acme\Domain\Blog\Repository\AuthorPostCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author/{id}",
     *     name="author"
     * )
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

        $countPost = $this->getPostAuthorCounterRepository()->count($author);
        $countComment = $this->getCommentAuthorCounterRepository()->count($author);

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
     * @return AuthorPostCounterRepository
     */
    private function getPostAuthorCounterRepository()
    {
        return $this->get('repository.counter.post_author');
    }

    /**
     * @return AuthorCommentCounterRepository
     */
    private function getCommentAuthorCounterRepository()
    {
        return $this->get('repository.counter.comment_author');
    }
}
