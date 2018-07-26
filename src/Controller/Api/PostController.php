<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Post;
use App\Service\GetHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @param GetHelper $helper
     * @return Response
     */
    public function index(Request $request, SerializerInterface $serializer, GetHelper $helper)
    {
        $categoryId = $request->get('category');

        if (!is_null($categoryId)) {
            $category = $helper->getCategory($categoryId);

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
     * @param GetHelper $helper
     * @return Response
     */
    public function store(Request $request, SerializerInterface $serializer, GetHelper $helper)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = new Post();
        $post->setName($request->get('name'));
        $post->setContent($request->get('content'));

        if (!is_null($request->get('categories'))) {
            $newCategories = $helper->toCategories($request->get('categories'));
            foreach ($newCategories as $category) {
                $post->addCategory($category);
            }
        }

        $entityManager->persist($post);
        $entityManager->flush();

        return new Response($serializer->serialize($post, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/posts/{id}", name="api.posts.show", methods="GET")
     * @param $id
     * @param SerializerInterface $serializer
     * @param GetHelper $helper
     * @return Response|JsonResponse
     */
    public function show($id, SerializerInterface $serializer, GetHelper $helper)
    {
        $post = $helper->getPost($id);

        return is_null($post)
            ? new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND)
            : new Response($serializer->serialize($post, 'json'), Response::HTTP_OK)
        ;
    }

    /**
     * @Route("/posts/{id}", name="api.post.update", methods="PUT")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param GetHelper $helper
     * @return Response
     */
    public function update($id, Request $request, SerializerInterface $serializer, GetHelper $helper)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        $post->setName($request->get('name'));
        $post->setContent($request->get('content'));

        if (!is_null($request->get('categories'))) {
            $newCategories = $helper->toCategories($request->get('categories'));
            $post->replaceCategories($newCategories);
        }
        $entityManager->flush();

        return new Response($serializer->serialize($post, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/posts/{id}", name="api.post.delete", methods="DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        $entityManager->remove($post);
        $entityManager->flush();
        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }
}
