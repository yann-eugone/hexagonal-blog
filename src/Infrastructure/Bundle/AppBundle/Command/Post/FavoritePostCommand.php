<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Post;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FavoritePostCommand extends AbstractPostCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:post:favorite')
            ->addArgument('author', InputArgument::REQUIRED)
            ->addArgument('post', InputArgument::REQUIRED)
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // Author
        if (($author = $input->getArgument('author')) !== null) {
            $author = $this->getAuthorRepository()->getByUsername($author);
        } else {
            $author = $this->askAuthor($input, $output);
        }
        $input->setArgument('author', $author);

        // Post
        if (($post = $input->getArgument('post')) !== null) {
            $post = $this->getPostRepository()->getById($post);
        } else {
            $post = $this->askPost($input, $output, 'Which post do you will to favorite ?');
        }
        $input->setArgument('post', $post);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getPostCommandFactory()->favoritePost(
            $input->getArgument('post'),
            $input->getArgument('author')
        );

        $this->getCommandBus()->handle($command);
    }
}
