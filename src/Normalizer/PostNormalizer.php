<?php

namespace App\Normalizer;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostNormalizer implements NormalizerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * PostNormalizer constructor.
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Post $object
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
            'content' => $object->getContent(),
            'file' => $object->getFile(),
            'fileType' => $object->getFileType(),
            'comments' => array_map(function (Comment $comment) {
                return [
                    'id' => $comment->getId(),
                    'author' => $comment->getAuthor(),
                    'content' => $comment->getContent(),
                    'created' => $comment->getCreatedAt()->format('Y.m.d')
                ];
            }, $object->getComments()->toArray()),
            'categories' => array_map(function (Category $category) {
                return [
                    'name' => $category->getName(),
                    'editLink' => $this->router->generate(
                        'category.update',
                        [
                            'id' => $category->getId()
                        ]
                    )
                ];
            }, $object->getCategories()->toArray())
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
        return $data instanceof Post;
    }
}