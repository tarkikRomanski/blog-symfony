<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ResponseHelper
{

    /**
     * @var GetHelper
     */
    private $getHelper;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * ResponseHelper constructor.
     * @param GetHelper $getHelper
     * @param SerializerInterface $serializer
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(GetHelper $getHelper, SerializerInterface $serializer)
    {
        $this->getHelper = $getHelper;
        $this->serializer = $serializer;

    }

    /**
     * Returns response by result
     *
     * @param $result
     * @param $errorResponse
     * @param $responseStatus
     * @param $responseErrorStatus
     * @return Response
     */
    public function byValidator(
        $result,
        $errorResponse = [],
        $responseStatus = Response::HTTP_OK,
        $responseErrorStatus = Response::HTTP_BAD_REQUEST
    ): Response {
        return ($result instanceof ConstraintViolationList)
            ? new Response($this->serializer->serialize($this->getHelper->toErrorsArray($result), 'json'),
                Response::HTTP_BAD_REQUEST)
            : $this->checkNull($result, $errorResponse, $responseStatus, $responseErrorStatus);
    }

    /**
     * Checks if result is null and return a result
     *
     * @param $result
     * @param $errorResponse
     * @param $responseStatus
     * @param $responseErrorStatus
     * @return Response|JsonResponse
     */
    public function checkNull(
        $result,
        $errorResponse,
        $responseStatus = Response::HTTP_OK,
        $responseErrorStatus = Response::HTTP_NOT_FOUND
    ) {
        return !is_null($result)
            ? new Response($this->serializer->serialize($result, 'json'), $responseStatus)
            : new JsonResponse($errorResponse, $responseErrorStatus);
    }
}