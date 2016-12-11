<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Comment;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteCommentCommand extends AbstractCommentCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:comment:delete')
            ->addArgument('comment', InputArgument::REQUIRED)
        ;
    }

    /**
     * @inheritDoc
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // Comment
        if (($comment = $input->getArgument('comment')) !== null) {
            $comment = $this->getCommentRepository()->getById($comment);
        } else {
            $comment = $this->askComment($input, $output, 'Which comment do you will to delete ?');
        }
        $input->setArgument('comment', $comment);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getCommentCommandFactory()->deleteComment($input->getArgument('comment'));

        $this->getCommandBus()->handle($command);
    }
}
