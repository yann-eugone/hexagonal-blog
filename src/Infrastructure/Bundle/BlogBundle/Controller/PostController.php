<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Controller;

use Acme\Application\Blog\Command\CommandBus;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\CreatePostType;
use Acme\Infrastructure\Bundle\BlogBundle\Form\Type\UpdatePostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    /**
     * @Route("/posts", name="post_list")
     * @Route("/posts/by-category/{category}", name="post_list_by_category", requirements={"category" = "\d+"})
     * @Route("/posts/by-tag/{tag}", name="post_list_by_tag", requirements={"tag" = "\d+"})
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request, $category = null, $tag = null)
    {
        $criteria = [];
        if ($category) {
            $criteria['category'] = $category;
        }
        if ($tag) {
            $criteria['tag'] = $tag;
        }

        $posts = $this->getRepository()->list($criteria);

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
        $command = $this->getCommandFactory()->newCreateCommand($this->getUser());
        $form = $this->getFormFactory()->create(CreatePostType::class, $command);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render(
                ':blog/post:create.html.twig',
                ['form' => $form->createView()]
            );
        }

        $this->getCommandBus()->handle($command);

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

        $command = $this->getCommandFactory()->newUpdateCommand($post);

        $form = $this->getFormFactory()->create(UpdatePostType::class, $command);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
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
    public function getRepository()
    {
        return $this->get('repository.post');
    }

    /**
     * @return PostCommandFactory
     */
    public function getCommandFactory()
    {
        return $this->get('command_factory.post');
    }

    /**
     * @return FormFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->get('form.factory');
    }

    /**
     * @return CommandBus
     */
    public function getCommandBus()
    {
        return $this->get('application_command_bus');
    }
}
