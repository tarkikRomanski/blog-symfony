<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Post;
use App\Service\GetHelper;
use App\Service\SetHelper;
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
     * @param SetHelper $helper
     * @return Response|JsonResponse
     */
    public function store(Request $request, SerializerInterface $serializer, SetHelper $helper)
    {
        $post = $helper->createPost(
            [
                'name' => $request->get('name'),
                'content' => $request->get('content'),
                'categories' => $request->get('categories')
            ]
        );

        return !is_null($post)
            ? new Response($serializer->serialize($post, 'json'), Response::HTTP_CREATED)
            : new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
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
            : new Response($serializer->serialize($post, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/posts/{id}", name="api.post.update", methods="PUT")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param SetHelper $helper
     * @return Response|JsonResponse
     */
    public function update($id, Request $request, SerializerInterface $serializer, SetHelper $helper)
    {
        $post = $helper->updatePost($id, [
            'name' => $request->get('name'),
            'content' => $request->get('content'),
            'categories' => $request->get('categories')
        ]);

        return !is_null($post)
            ? new Response($serializer->serialize($post, 'json'), Response::HTTP_CREATED)
            : new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/posts/{id}", name="api.post.delete", methods="DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        if(!is_null($post)) {
            $entityManager->remove($post);
            $entityManager->flush();
            return new JsonResponse(['id' => $id], Response::HTTP_OK);
        }

        return new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }
}
