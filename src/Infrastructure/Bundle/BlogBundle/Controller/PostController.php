<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Controller;

use Acme\Application\Blog\Command\CreatePost;
use Acme\Application\Blog\Command\UpdatePost;
use Acme\Domain\Blog\Exception\PostNotFoundException;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\CreatePostType;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\UpdatePostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SimpleBus\Message\Bus\MessageBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * @Route("/posts", name="post_list", defaults={"_format" = "html"})
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $posts = $this->getRepository()->list([]);

        return $this->render(
            ':blog/post:list.html.twig',
            ['posts' => $posts]
        );
    }

    /**
     * @Route("/posts/{id}", name="post", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function showAction($id, Request $request)
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
     * @Route("/posts/create", name="post_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->getFormFactory()->create(
            CreatePostType::class,
            new CreatePost(null, null, null, $this->getUser())
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                ':blog/post:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        /** @var $command CreatePost */
        $command = $form->getData();

        $this->getMessageBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $command->getPost()->getId()]);
    }

    /**
     * @Route("/posts/{id}/update", name="post_update", requirements={"id" = "\d+"})
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

        $form = $this->getFormFactory()->create(
            UpdatePostType::class,
            UpdatePost::fromPost($post)
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                ':blog/post:update.html.twig',
                ['post' => $post, 'form' => $form->createView()]
            );
        }

        /** @var $command UpdatePost */
        $command = $form->getData();

        $this->getMessageBus()->handle($command);

        return $this->redirectToRoute('post', ['id' => $post->getId()]);
    }

    /**
     * @return PostRepository
     */
    public function getRepository()
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
