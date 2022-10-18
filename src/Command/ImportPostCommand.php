<?php

// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\Post;
use App\Helpers\PostImport;
use App\Repository\PostRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'post:import')]
class ImportPostCommand extends Command
{

    protected static $defaultDescription = 'Imports posts';

    protected int $createdCount;

    protected int $updatedCount;

    public function __construct(private PostRepository $postRepository)
    {
        parent::__construct();
        $this->postRepository = $postRepository;
        $this->createdCount = 0;
        $this->updatedCount = 0;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import Post List')
            ->setHelp('This command helps you import post from punchng.com');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $crawler = new Crawler(file_get_contents('https://rss.punchng.com/v1/category/latest_news'));
        $postRepo = $this->postRepository;

        $io = new SymfonyStyle($input, $output);

        $crawler->filterXPath('//channel/item')->each(

            function (Crawler $node, $i) use ($postRepo) {

                $postExists = $postRepo->findOneBy(['title' => $node->filterXPath('//title')->text()]);

                $post = $postExists ? $postExists : new Post;

                $this->updateOrCreatePost($node, $post, $postRepo, $postExists);
            }
        );

        $io->success("$this->createdCount Punch news imported! : $this->updatedCount Punch news updated!");

        return Command::SUCCESS;
    }


    private function updateOrCreatePost($node, $post, $postRepo, $updatable = null)
    {
        $post->setTitle($node->filterXPath('//title')->text());
        $post->setDescription($node->filterXPath('//description')->text());
        $post->setPicture($node->filterXPath('//enclosure')->extract(['url'])[0]);

        if ($updatable) {
            $post->setUpdatedAt(new DateTime());
            $this->updatedCount += 1;
        } else {
            $post->setCreatedAt(new DateTimeImmutable());
            $this->createdCount += 1;
        }

        $postRepo->save($post, true);
    }
}
