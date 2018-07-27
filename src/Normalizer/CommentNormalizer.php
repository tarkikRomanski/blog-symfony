<?php

namespace App\Normalizer;

use App\Entity\Comment;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CommentNormalizer implements NormalizerInterface
{
    /**
     * @param Comment $object
     * @param null $format
     * @param array $context
     *
     * @return mixed
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'author' => $object->getAuthor(),
            'content' => $object->getContent(),
            'created' => $object->getCreatedAt()->format('Y.m.d')
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
        return $data instanceof Comment;
    }
}