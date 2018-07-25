<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PostController
 * @package App\Controller\Api
 * @Route("/api")
 */
class PostController extends Controller
{
    /**
     * @Route("/posts", name="api.post.index", methods="GET")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, SerializerInterface $serializer)
    {
        $categoryId = $request->get('category');

        if(!is_null($categoryId)) {
            $category = $this->getDoctrine()
                ->getRepository(Category::class)
                ->find($categoryId);

            $posts = !is_null($category) ? $category->getPosts() : [];
            return new Response($serializer->serialize($posts, 'json'), Response::HTTP_OK);
        }

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        return new Response($serializer->serialize($posts, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/posts", name="api.post.store", methods="POST")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function store(Request $request, SerializerInterface $serializer) {
        $post = new Post();
        
        $post->setName($request->get('name'));
        $post->setContent($request->get('content'));

        return new Response($serializer->serialize($post, 'json'), Response::HTTP_CREATED);
    }
}
