<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\FavoritePost;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\FavoriteRepository;
use Acme\Domain\Blog\Repository\PostRepository;

class FavoritePostHandler
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var FavoriteRepository
     */
    private $favoriteRepository;

    /**
     * @var PostEventFactory
     */
    private $eventFactory;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @param PostRepository     $postRepository
     * @param AuthorRepository   $authorRepository
     * @param FavoriteRepository $favoriteRepository
     * @param PostEventFactory   $eventFactory
     * @param EventBus           $eventBus
     */
    public function __construct(
        PostRepository $postRepository,
        AuthorRepository $authorRepository,
        FavoriteRepository $favoriteRepository,
        PostEventFactory $eventFactory,
        EventBus $eventBus
    ) {
        $this->postRepository = $postRepository;
        $this->authorRepository = $authorRepository;
        $this->favoriteRepository = $favoriteRepository;
        $this->eventFactory = $eventFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @param FavoritePost $command
     */
    public function __invoke(FavoritePost $command)
    {
        $post = $this->postRepository->getById($command->getPostId());
        $author = $this->authorRepository->getById($command->getAuthorId());

        $this->favoriteRepository->add($post, $author);

        $this->eventBus->dispatch($this->eventFactory->postFavorited($post, $author, $command->getDate()));
    }
}
