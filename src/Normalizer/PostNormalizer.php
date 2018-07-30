<?php

namespace App\Normalizer;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostNormalizer implements NormalizerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * PostNormalizer constructor.
     * @param UrlGeneratorInterface $router
     * @param RequestStack $requestStack
     */
    public function __construct(UrlGeneratorInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->request = $requestStack->getCurrentRequest();
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
            'file' => !is_null($object->getFile())
                ? $this->request->getUriForPath('/uploads/posts/' . $object->getFile())
                : null,
            'fileType' => $object->getFileType(),
            'link' => $this->router->generate('post.get', ['id' => $object->getId()]),
            'editLink' => $this->router->generate('post.update', ['id' => $object->getId()]),
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
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'link' => $this->router->generate(
                        'category.get',
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