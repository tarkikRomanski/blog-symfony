<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Service\GetHelper;
use App\Service\SetHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CategoryController
 * @package App\Controller\Api
 * @Route("/api")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="api.category.index", methods="GET")
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(SerializerInterface $serializer)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return new Response($serializer->serialize($categories, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/categories/{id}", name="api.category.show", methods="GET")
     * @param $id
     * @param SerializerInterface $serializer
     * @param GetHelper $helper
     * @return Response|JsonResponse
     */
    public function show($id, SerializerInterface $serializer, GetHelper $helper)
    {
        $category = $helper->getCategory($id);

        return !is_null($category)
            ? new Response($serializer->serialize($category, 'json'), Response::HTTP_OK)
            : new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/categories/{id}", name="api.category.update", methods="PUT")
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param SetHelper $helper
     * @return Response|JsonResponse
     */
    public function update($id, Request $request, SerializerInterface $serializer, SetHelper $helper)
    {
        $category = $helper->updateCategory($id, [
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        return !is_null($category)
            ? new Response($serializer->serialize($category, 'json'), Response::HTTP_OK)
            : new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route("/categories", name="api.category.create", methods="POST")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param SetHelper $helper
     * @return Response
     */
    public function store(Request $request, SerializerInterface $serializer, SetHelper $helper)
    {
        $category = $helper->createCategory(
            [
                'name' => $request->get('name'),
                'description' => $request->get('description')
            ]
        );

        return new Response($serializer->serialize($category, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/categories/{id}", name="api.category.delete", methods="DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (!is_null($category)) {
            $entityManager->remove($category);
            $entityManager->flush();
            return new JsonResponse(['id' => $id], Response::HTTP_OK);
        }

        return new JsonResponse(['id' => $id], Response::HTTP_NOT_FOUND);
    }
}
