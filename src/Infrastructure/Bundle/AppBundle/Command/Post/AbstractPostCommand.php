<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Post;

use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Model\Tag;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\CategoryRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Domain\Blog\Repository\TagRepository;
use Acme\Infrastructure\Bundle\AppBundle\Command\AbstractCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

abstract class AbstractPostCommand extends AbstractCommand
{
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $message
     *
     * @return Post
     */
    protected function askPost(InputInterface $input, OutputInterface $output, $message)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question($message . ' ');
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
     * @return Category
     */
    protected function askCategory(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $choicesCategory = [];
        foreach ($this->getCategoryRepository()->list() as $category) {
            $choicesCategory[(string) $category] = $category;
        }

        $question = new ChoiceQuestion('Please select post category : ', $choicesCategory);
        $selectedCategory = $helper->ask($input, $output, $question);

        return $choicesCategory[$selectedCategory];
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Tag[]
     */
    protected function askTags(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $choicesTag = [];
        foreach ($this->getTagRepository()->list() as $tag) {
            $choicesTag[(string) $tag] = $tag;
        }

        $question = new ChoiceQuestion('Please select post tags : ', $choicesTag);
        $question->setMultiselect(true);
        $selectedTags = $helper->ask($input, $output, $question);

        return array_map(
            function ($selectedTag) use ($choicesTag) {
                return $choicesTag[$selectedTag];
            },
            $selectedTags
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function askTitle(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Please enter post title : ');

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function askBody(InputInterface $input, OutputInterface $output)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new Question('Please enter post body : ');

        return $helper->ask($input, $output, $question);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param bool            $confirmation
     *
     * @return null|string
     */
    protected function askSummary(InputInterface $input, OutputInterface $output, $confirmation)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        if ($confirmation) {
            $question = new ConfirmationQuestion('Do you want to define summary based on body ? ', true);
            if ($helper->ask($input, $output, $question)) {
                return null;
            }
        }

        $questionTitle = new Question('Please enter post summary : ');

        return $helper->ask($input, $output, $questionTitle);
    }

    /**
     * @return PostRepository
     */
    protected function getPostRepository()
    {
        return $this->getContainer()->get('repository.post');
    }

    /**
     * @return PostCommandFactory
     */
    protected function getPostCommandFactory()
    {
        return $this->getContainer()->get('command_factory.post');
    }

    /**
     * @return CategoryRepository
     */
    protected function getCategoryRepository()
    {
        return $this->getContainer()->get('repository.category');
    }

    /**
     * @return TagRepository
     */
    protected function getTagRepository()
    {
        return $this->getContainer()->get('repository.tag');
    }

    /**
     * @return AuthorRepository
     */
    protected function getAuthorRepository()
    {
        return $this->getContainer()->get('repository.author');
    }
}
