<?php

namespace App\Normalizer;


use App\Entity\Category;
use App\Entity\Comment;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryNormalizer implements NormalizerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CategoryNormalizer constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Category $object
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'editLink' => $this->container->get('router')->generate('category.update', ['id' => $object->getId()]),
            'link' => $this->container->get('router')->generate('category.get', ['id' => $object->getId()]),
            'postsQuantity' => $object->getPosts()->count(),
            'comments' => array_map(function (Comment $comment) {
                return [
                    'id' => $comment->getId(),
                    'author' => $comment->getAuthor(),
                    'content' => $comment->getContent(),
                    'created' => $comment->getCreatedAt()->format('Y.m.d')
                ];
            }, $object->getComments()->toArray()),
        ];
    }

    /**
     * @param mixed $data
     * @param null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Category;
    }
}