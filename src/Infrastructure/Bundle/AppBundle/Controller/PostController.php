<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Application\Common\Command\CommandBus;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\CreatePostType;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\UpdatePostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends AbstractController
{
    /**
     * @Route("/posts",
     *     name="post_list"
     * )
     * @Route("/posts/by-category/{category}",
     *     name="post_list_by_category",
     *     requirements={"category" = "\d+"}
     * )
     * @Route("/posts/by-tag/{tag}",
     *     name="post_list_by_tag",
     *     requirements={"tag" = "\d+"}
     * )
     * @Route("/posts/by-author/{author}",
     *     name="post_list_by_author",
     *     requirements={"author" = "\d+"}
     * )
     * @Method("GET")
     *
     * @param int|null $category
     * @param int|null $tag
     * @param int|null $author
     *
     * @return Response
     */
    public function listAction($category = null, $tag = null, $author = null)
    {
        $criteria = [];
        if ($category !== null) {
            $criteria['category'] = $category;
        }
        if ($tag !== null) {
            $criteria['tag'] = $tag;
        }
        if ($author !== null) {
            $criteria['author'] = $author;
        }

        $posts = $this->getRepository()->search($criteria);

        return $this->render(
            ':blog/post:list.html.twig',
            ['posts' => $posts]
        );
    }

    /**
     * @Route("/posts/{id}",
     *     name="post",
     *     requirements={"id" = "\d+"}
     * )
     * @Method("GET")
     *
     * @param int $id
     *
     * @return Response
     */
    public function showAction($id)
    {
        try {
            $post = $this->getRepository()->getById($id);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        return $this->render(
            ':blog/post:show.html.twig',
            ['post' => $post]
        );
    }

    /**
     * @Route("/posts/create",
     *     name="post_create"
     * )
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $author = $this->getAuthor();
        if ($author === null) {
            throw $this->createAccessDeniedException();
        }

        $command = $this->getCommandFactory()->createPost($author);
        $form = $this->getFormFactory()->create(CreatePostType::class, $command);

        $form->handleRequest($request);

        if (!$this->isFormProcessable($form)) {
            return $this->render(
                ':blog/post:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        $this->getCommandBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $command->getPost()->getId()]);
    }

    /**
     * @Route("/posts/{id}/update",
     *     name="post_update",
     *     requirements={"id" = "\d+"}
     * )
     * @Method({"GET", "POST"})
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function updateAction($id, Request $request)
    {
        try {
            $post = $this->getRepository()->getById($id);
        } catch (PostNotFoundException $exception) {
            throw new NotFoundHttpException(null, $exception);
        }

        $command = $this->getCommandFactory()->updatePost($post);

        $form = $this->getFormFactory()->create(UpdatePostType::class, $command);

        $form->handleRequest($request);

        if (!$this->isFormProcessable($form)) {
            return $this->render(
                ':blog/post:update.html.twig',
                ['post' => $post, 'form' => $form->createView()]
            );
        }

        $this->getCommandBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @return PostRepository
     */
    private function getRepository()
    {
        return $this->get('repository.post');
    }

    /**
     * @return PostCommandFactory
     */
    private function getCommandFactory()
    {
        return $this->get('command_factory.post');
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
