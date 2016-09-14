<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Controller;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\CreateCommentType;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\UpdateCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller
{
    /**
     * @Route("/posts/{id}/comments", name="post_add_comment")
     * @Method({"GET", "POST"})
     *
     * @param int     $postId
     * @param Request $request
     *
     * @return Response
     */
    public function listAction($postId, Request $request)
    {
        try {
            $post = $this->getPostRepository()->getById($postId);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $comments = $this->getRepository()->list($post, []);

        return $this->render(
            ':blog/comment:list.html.twig',
            ['comments' => $comments]
        );
    }

    /**
     * @Route("/posts/{postId}/comments/add", name="post_add_comment", requirements={"postId" = "\d+"})
     * @Method({"GET", "POST"})
     *
     * @param int     $postId
     * @param Request $request
     *
     * @return Response
     */
    public function createAction($postId, Request $request)
    {
        try {
            $post = $this->getPostRepository()->getById($postId);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $form = $this->getFormFactory()->create(
            CreateCommentType::class,
            new CreateComment(null, $this->getUser(), $post),
            [
                'action' => $this->generateUrl('post_add_comment', ['postId' => $postId]),
                'method' => 'post'
            ]
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                ':blog/comment:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        /** @var $command CreateComment */
        $command = $form->getData();

        $this->getMessageBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @Route("/posts/{postId}/comments/{id}/update", name="post_update_comment", requirements={"postId" = "\d+", "id" = "\d+"})
     * @Method({"GET", "POST"})
     *
     * @param int     $postId
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction($postId, $id, Request $request)
    {
        try {
            $post = $this->getPostRepository()->getById($postId);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        try {
            $comment = $this->getRepository()->getById($id);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $form = $this->getFormFactory()->create(
            UpdateCommentType::class,
            UpdateComment::fromComment($comment),
            [
                'action' => $this->generateUrl('post_update_comment', ['postId' => $postId, 'id' => $id]),
                'method' => 'post'
            ]
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                ':blog/comment:update.html.twig',
                ['comment' => $comment, 'form' => $form->createView()]
            );
        }

        /** @var $command UpdateComment */
        $command = $form->getData();

        $this->getMessageBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @return CommentRepository
     */
    public function getRepository()
    {
        return $this->get('repository.comment');
    }

    /**
     * @return PostRepository
     */
    public function getPostRepository()
    {
        return $this->get('repository.post');
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->get('form.factory');
    }

    /**
     * @return MessageBus
     */
    public function getMessageBus()
    {
        return $this->get('command_bus');
    }
}
