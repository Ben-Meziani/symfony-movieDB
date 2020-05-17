<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/{id}/view", name="post_view", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function viewPost(Post $post)
    {
        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/list", name="post_list", methods={"GET"})
     */
    public function listPosts()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

}