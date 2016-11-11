<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Application\Blog\Command\CommandBus;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\CreatePostType;
use Acme\Infrastructure\Bundle\AppBundle\Form\Type\UpdatePostType;
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
     * @param int|null $category
     * @param int|null $tag
     *
     * @return Response
     */
    public function listAction($category = null, $tag = null)
    {
        $criteria = [];
        if ($category) {
            $criteria['category'] = $category;
        }
        if ($tag) {
            $criteria['tag'] = $tag;
        }

        $posts = $this->getRepository()->search($criteria);

        return $this->render(
            ':blog/post:list.html.twig',
            ['posts' => $posts]
        );
    }

    /**
     * @Route("/posts/{id}", name="post", requirements={"id" = "\d+"})
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
