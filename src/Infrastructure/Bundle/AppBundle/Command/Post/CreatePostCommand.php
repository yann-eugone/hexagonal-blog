<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command\Post;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePostCommand extends AbstractPostCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('blog:post:create')
            ->addArgument('author', InputArgument::REQUIRED)
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

        // Category
        if (($category = $input->getOption('category')) !== null) {
            $category = $this->getCategoryRepository()->getById($category);
        } else {
            $category = $this->askCategory($input, $output);
        }
        $input->setOption('category', $category);

        // Tags
        if (($tags = $input->getOption('tags')) !== null) {
            $tags = array_map(
                function ($tag) {
                    return $this->getTagRepository()->getById($tag);
                },
                explode(',', $tags)
            );
        } else {
            $tags = $this->askTags($input, $output);
        }
        $input->setOption('tags', $tags);

        // Title
        if ($input->getOption('title') === null) {
            $input->setOption('title', $this->askTitle($input, $output));
        }

        // Body
        if ($input->getOption('body') === null) {
            $input->setOption('body', $this->askBody($input, $output));
        }

        // Summary
        if ($input->getOption('summary') === null) {
            $input->setOption('summary', $this->askSummary($input, $output, true));
        }
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isGranted('create_post')) {
            $output->writeln('<error>You are not allowed to create a Post.</error>');

            return 1;
        }

        $command = $this->getPostCommandFactory()->createPost($input->getArgument('author'));
        $command->setCategory($input->getOption('category'));
        $command->setTags($input->getOption('tags'));
        $command->setTitle($input->getOption('title'));
        $command->setBody($input->getOption('body'));
        $command->setSummary($input->getOption('summary') ?: substr($input->getOption('body'), 200));

        $this->getCommandBus()->handle($command);

        return 0;
    }
}
