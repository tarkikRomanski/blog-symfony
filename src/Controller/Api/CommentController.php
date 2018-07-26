<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Service\SetHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CommentController
 * @package App\Controller\Api
 * @Route("/api")
 */
class CommentController extends Controller
{
    /**
     * @Route("/comments", name="api.comment.store", methods="POST")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function store(Request $request, SerializerInterface $serializer, SetHelper $helper)
    {
        $comment = $helper->createComment(
            [
                'author' => $request->get('author'),
                'content' => $request->get('content'),
                'post_id' => $request->get('post'),
                'category_id' => $request->get('category')
            ]
        );

        return new Response($serializer->serialize($comment, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/comments/{id}", name="api.comment.delete", methods="DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        $entityManager->remove($comment);
        $entityManager->flush();
        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }
}
