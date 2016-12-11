<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Comment;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommentCommand extends AbstractCommentCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:comment:update')
            ->addArgument('comment', InputArgument::REQUIRED)
            ->addOption('text', null, InputOption::VALUE_REQUIRED)
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
            $comment = $this->askComment($input, $output, 'Which comment do you will to update ?');
        }
        $input->setArgument('comment', $comment);

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
        $command = $this->getCommentCommandFactory()->updateComment($input->getArgument('comment'));
        $command->setText($input->getOption('text'));

        $this->getCommandBus()->handle($command);
    }
}
