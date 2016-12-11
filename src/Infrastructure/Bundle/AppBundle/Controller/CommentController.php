<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Application\Blog\Command\Comment\CommentCommandFactory;
use Acme\Application\Common\Command\CommandBus;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\CreateCommentType;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\UpdateCommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends AbstractController
{
    /**
     * @Route("/posts/{postId}/comments",
     *     name="post_list_comment"
     * )
     * @Method({"GET", "POST"})
     *
     * @param int $postId
     *
     * @return Response
     */
    public function listAction($postId)
    {
        try {
            $post = $this->getPostRepository()->getById($postId);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $comments = $this->getRepository()->search($post, []);

        return $this->render(
            ':blog/comment:list.html.twig',
            ['comments' => $comments]
        );
    }

    /**
     * @Route("/posts/{postId}/comments/add",
     *     name="post_add_comment",
     *     requirements={"postId" = "\d+"}
     * )
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

        $author = $this->getAuthor();
        if ($author === null) {
            throw $this->createAccessDeniedException();
        }

        $command = $this->getCommandFactory()->newCreateCommand($author, $post);

        $form = $this->getFormFactory()->create(
            CreateCommentType::class,
            $command,
            [
                'action' => $this->generateUrl('post_add_comment', ['postId' => $postId]),
                'method' => 'post'
            ]
        );

        $form->handleRequest($request);

        if (!$this->isFormProcessable($form)) {
            return $this->render(
                ':blog/comment:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        $this->getCommandBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @Route("/posts/{postId}/comments/{id}/update",
     *     name="post_update_comment",
     *     requirements={"postId" = "\d+", "id" = "\d+"}
     * )
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

        $command = $this->getCommandFactory()->newUpdateCommand($comment);

        $form = $this->getFormFactory()->create(
            UpdateCommentType::class,
            $command,
            [
                'action' => $this->generateUrl('post_update_comment', ['postId' => $postId, 'id' => $id]),
                'method' => 'post'
            ]
        );

        $form->handleRequest($request);

        if (!$this->isFormProcessable($form)) {
            return $this->render(
                ':blog/comment:update.html.twig',
                ['comment' => $comment, 'form' => $form->createView()]
            );
        }

        $this->getCommandBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @return CommentRepository
     */
    private function getRepository()
    {
        return $this->get('repository.comment');
    }

    /**
     * @return PostRepository
     */
    private function getPostRepository()
    {
        return $this->get('repository.post');
    }

    /**
     * @return CommentCommandFactory
     */
    private function getCommandFactory()
    {
        return $this->get('command_factory.comment');
    }

    /**
     * @return FormFactoryInterface
     */
    private function getFormFactory()
    {
        return $this->get('form.factory');
    }

    /**
     * @return CommandBus
     */
    private function getCommandBus()
    {
        return $this->get('application_command_bus');
    }
}
