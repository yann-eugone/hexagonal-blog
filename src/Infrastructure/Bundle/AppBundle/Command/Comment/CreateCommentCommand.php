<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Comment;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommentCommand extends AbstractCommentCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:comment:create')
            ->addOption('post', null, InputOption::VALUE_REQUIRED)
            ->addOption('author', null, InputOption::VALUE_REQUIRED)
            ->addOption('text', null, InputOption::VALUE_REQUIRED)
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // Author
        if (($author = $input->getOption('author')) !== null) {
            $author = $this->getAuthorRepository()->getByUsername($author);
        } else {
            $author = $this->askAuthor($input, $output);
        }
        $input->setOption('author', $author);

        // Post
        if (($post = $input->getOption('post')) !== null) {
            $post = $this->getPostRepository()->getById($post);
        } else {
            $post = $this->askPost($input, $output);
        }
        $input->setOption('post', $post);

        // Text
        if ($input->getOption('text') === null) {
            $input->setOption('text', $this->askText($input, $output));
        }
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getCommentCommandFactory()->createComment(
            $input->getOption('author'),
            $input->getOption('post')
        );
        $command->setText($input->getOption('text'));

        $this->getCommandBus()->handle($command);
    }
}
