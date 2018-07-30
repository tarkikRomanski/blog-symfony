<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use App\Service\ResponseHelper;
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
     * @param ResponseHelper $responseHelper
     * @param SetHelper $setHelper
     * @return Response
     */
    public function store(Request $request, ResponseHelper $responseHelper, SetHelper $setHelper)
    {
        $result = $setHelper->createComment(
            [
                'author' => $request->get('author'),
                'content' => $request->get('content'),
                'post_id' => $request->get('post_id'),
                'category_id' => $request->get('category_id')
            ]
        );

        return $responseHelper->byValidator($result);
    }

    /**
     * @Route("/comments/{id}", name="api.comment.delete", methods="DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository(Comment::class)->find($id);
        if (!is_null($comment)) {
            $entityManager->remove($comment);
            $entityManager->flush();
            return new JsonResponse(['id' => $id], Response::HTTP_OK);
        }

        return new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }
}
