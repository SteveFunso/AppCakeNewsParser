<?php

namespace App\Helpers;

use App\Entity\Post;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostImport
{
    public function __invoke(ManagerRegistry $doctrine)
    {

        $posts = simplexml_load_file('https://rss.punchng.com/v1/category/latest_news');
        
        if($posts){
            foreach ($posts->channel->item as $post) {
                $postModel = new Post;

                $postModel->setTitle($post->title);
                $postModel->setImage($post->enclosure['@attributes']->url);
                $postModel->setDescription($post->description ?? 'nill');
                // $postModel->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', strtotime('now')));

                // actually executes the queries (i.e. the INSERT query)
                $this->doctrine->flush();
            }
        }
    }
}