<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostsController extends AbstractController
{

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
    
    #[Route('/', name: 'post_list')]
    public function index(ManagerRegistry $doctrine, PaginatorInterface $paginator, Request $request): Response
    {
        if ($this->getUser()) {
            $postsRepo = $doctrine->getRepository(Post::class);

            $posts = $paginator->paginate(
                $postsRepo->findAll(), /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                8 /*limit per page*/
            );

            return $this->render('posts/index.html.twig', compact('posts'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    #[Route('/posts/{post}', name: 'post_delete', methods: ["GET", "DELETE"] )]
    public function destroy(PostRepository $postsRepo, Post $post): Response
    {
        if(! $this->getUser()){
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }
        
        $postsRepo->remove($post, true);

        return new RedirectResponse($this->urlGenerator->generate('post_list'));
    }
}