<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Comment;

use Acme\Application\Blog\Command\Comment\CommentCommandFactory;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Infrastructure\Bundle\AppBundle\Command\AbstractCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractCommentCommand extends AbstractCommand
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $message
     *
     * @return Comment
     */
    protected function askComment(InputInterface $input, OutputInterface $output, $message)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question($message . ' ');
        $question->setValidator(function ($answer) {
            return $this->getCommentRepository()->getById($answer);
        });

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Post
     */
    protected function askPost(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Which post do you will to comment ? ');
        $question->setValidator(function ($answer) {
            return $this->getPostRepository()->getById($answer);
        });

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Author
     */
    protected function askAuthor(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Who are you ? ');
        $question->setValidator(function ($answer) {
            return $this->getAuthorRepository()->getByUsername($answer);
        });

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function askText(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Please enter comment : ');

        return $helper->ask($input, $output, $question);
    }

    /**
     * @return CommentCommandFactory
     */
    protected function getCommentCommandFactory()
    {
        return $this->getContainer()->get('command_factory.comment');
    }

    /**
     * @return CommentRepository
     */
    protected function getCommentRepository()
    {
        return $this->getContainer()->get('repository.comment');
    }

    /**
     * @return PostRepository
     */
    protected function getPostRepository()
    {
        return $this->getContainer()->get('repository.post');
    }

    /**
     * @return AuthorRepository
     */
    protected function getAuthorRepository()
    {
        return $this->getContainer()->get('repository.author');
    }
}
