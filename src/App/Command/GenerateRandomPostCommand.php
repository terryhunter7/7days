<?php

namespace App\Command;

use DateTimeImmutable;
use Domain\Post\PostManager;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomPostCommand extends Command
{
    protected static $defaultName = 'app:generate-random-post';
    protected static $defaultDescription = 'Run app:generate-random-post';

    protected PostManager $postManager;
    protected LoremIpsum $loremIpsum;

    public function __construct(PostManager $postManager, LoremIpsum $loremIpsum, string $name = null)
    {
        parent::__construct($name);
        $this->postManager = $postManager;
        $this->loremIpsum = $loremIpsum;
    }

    protected function configure(): void
    {
        $this->addOption(
            'custom_post',
            null,
            InputOption::VALUE_OPTIONAL,
            'Add Custom post with summary date title and one paragraph'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isCustom = false;
        $title = $this->loremIpsum->words(mt_rand(4, 6));
        $numberOfParagraphs = 2;

        if ((int)$input->getOption('custom_post')) {
            $isCustom = true;
            $date = new DateTimeImmutable();
            $title = sprintf('Summary %s', $date->format('Y-m-d'));
            $numberOfParagraphs = 1;
        }

        $content = $this->loremIpsum->paragraphs($numberOfParagraphs);

        $this->postManager->addPost($title, $content);

        $output->writeln(sprintf('A %s post has been generated.', $isCustom ? 'Custom' : 'Random'));

        return Command::SUCCESS;
    }
}
