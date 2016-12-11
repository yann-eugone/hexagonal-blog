<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Post;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeletePostCommand extends AbstractPostCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:post:delete')
            ->addArgument('post', InputArgument::REQUIRED)
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // Post
        if (($post = $input->getArgument('post')) !== null) {
            $post = $this->getPostRepository()->getById($post);
        } else {
            $post = $this->askPost($input, $output, 'Which post do you will to delete ?');
        }
        $input->setArgument('post', $post);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getPostCommandFactory()->deletePost($input->getArgument('post'));

        $this->getCommandBus()->handle($command);
    }
}
