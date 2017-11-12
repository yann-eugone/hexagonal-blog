<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Post;

use Closure;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class UpdatePostCommand extends AbstractPostCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:post:update')
            ->addArgument('author', InputArgument::REQUIRED)
            ->addArgument('post', InputArgument::REQUIRED)
            ->addOption('category', null, InputOption::VALUE_REQUIRED)
            ->addOption('tags', null, InputOption::VALUE_REQUIRED)
            ->addOption('title', null, InputOption::VALUE_REQUIRED)
            ->addOption('summary', null, InputOption::VALUE_REQUIRED)
            ->addOption('body', null, InputOption::VALUE_REQUIRED)
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
        $this->authenticate($author);

        // Post
        if (($post = $input->getArgument('post')) !== null) {
            $post = $this->getPostRepository()->getById($post);
        } else {
            $post = $this->askPost($input, $output, 'Which post do you will to update ?');
        }
        $input->setArgument('post', $post);

        // Category
        if (($category = $input->getOption('category')) !== null) {
            $category = $this->getCategoryRepository()->getById($category);
        } else {
            $category = $this->askChange(
                $input,
                $output,
                'category',
                function (InputInterface $input, OutputInterface $output) {
                    return $this->askCategory($input, $output);
                }
            );
        }
        $input->setOption('category', $category);

        if (($tags = $input->getOption('tags')) !== null) {
            $tags = array_map(
                function ($tag) {
                    return $this->getTagRepository()->getById($tag);
                },
                explode(',', $tags)
            );
        } else {
            $tags = $this->askChange(
                $input,
                $output,
                'tags',
                function (InputInterface $input, OutputInterface $output) {
                    return $this->askTags($input, $output);
                }
            );
        }
        $input->setOption('tags', $tags);

        // Title
        if ($input->getOption('title') === null) {
            $input->setOption(
                'title',
                $this->askChange(
                    $input,
                    $output,
                    'title',
                    function (InputInterface $input, OutputInterface $output) {
                        return $this->askTitle($input, $output);
                    }
                )
            );
        }

        // Body
        if ($input->getOption('body') === null) {
            $input->setOption(
                'body',
                $this->askChange(
                    $input,
                    $output,
                    'body',
                    function (InputInterface $input, OutputInterface $output) {
                        return $this->askBody($input, $output);
                    }
                )
            );
        }

        // Summary
        if ($input->getOption('summary') === null) {
            $input->setOption(
                'summary',
                $this->askChange(
                    $input,
                    $output,
                    'summary',
                    function (InputInterface $input, OutputInterface $output) {
                        return $this->askSummary($input, $output, $input->getOption('body') !== null);
                    }
                )
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isGranted('update', $input->getArgument('post'))) {
            $output->writeln('<error>You are not allowed to update this Post.</error>');

            return 1;
        }

        $command = $this->getPostCommandFactory()->updatePost($input->getArgument('post'));
        if (($category = $input->getOption('category')) !== null) {
            $command->setCategory($category);
        }
        if (($tags = $input->getOption('tags')) !== null) {
            $command->setTags($tags);
        }
        if (($title = $input->getOption('title')) !== null) {
            $command->setTitle($title);
        }
        if (($summary = $input->getOption('summary')) !== null) {
            $command->setSummary($summary);
        }
        if (($body = $input->getOption('body')) !== null) {
            $command->setBody($body);
        }

        $this->getCommandBus()->handle($command);

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $property
     * @param Closure         $callback
     *
     * @return mixed
     */
    private function askChange(InputInterface $input, OutputInterface $output, $property, $callback)
    {
        /** @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion(sprintf('Do you want to change post %s ? ', $property), false);

        if (!$helper->ask($input, $output, $question)) {
            return null;
        }

        return $callback($input, $output);
    }
}
