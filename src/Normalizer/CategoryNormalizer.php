<?php

namespace App\Normalizer;


use App\Entity\Category;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryNormalizer implements NormalizerInterface
{
    /**
     * @param Category $object
     * @param null $format
     * @param array $context
     *
     * @return mixed
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
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