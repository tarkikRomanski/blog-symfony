<?php

namespace App\Service;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SetHelper
{
    private $doctrine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var GetHelper
     */
    private $getHelper;

    /**
     * @var Uploader
     */
    private $uploader;

    private $validator;

    /**
     * SetHelper constructor.
     * @param ContainerInterface $container
     * @param GetHelper $getHelper
     * @param Uploader $uploader
     * @param ValidatorInterface $validator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(
        ContainerInterface $container,
        GetHelper $getHelper,
        Uploader $uploader,
        ValidatorInterface $validator
    ) {
        $this->container = $container;
        $this->getHelper = $getHelper;
        $this->uploader = $uploader;
        $this->validator = $validator;

        if (!$container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".');
        }

        $this->doctrine = $container->get('doctrine');
    }

    /**
     * Creates new record into posts table
     *
     * @param array $data
     * @return Post
     */
    public function createPost(array $data): Post
    {
        $entityManager = $this->doctrine->getManager();
        $post = new Post();
        $post->setName($data['name']);
        $post->setContent($data['content']);

        if (isset($data['categories']) && !is_null($data['categories'])) {
            $newCategories = $this->getHelper->toCategories($data['categories'])->toArray();
            foreach ($newCategories as $category) {
                $post->addCategory($category);
            }
        }

        if (isset($data['file']) && !is_null($data['file'])) {
            $file = $this->uploader->upload($data['file']);
            $post->setFile($file['name']);
            $post->setFileType($file['type']);
        }

        $entityManager->persist($post);
        $entityManager->flush();

        return $post;
    }

    /**
     * Updates exist record into posts table
     *
     * @param $id
     * @param array $data
     * @return Post
     */
    public function updatePost($id, array $data): Post
    {
        $entityManager = $this->doctrine->getManager();
        $post = $entityManager->getRepository(Post::class)->find($id);
        if (!is_null($post)) {
            $post->setName($data['name']);
            $post->setContent($data['content']);

            if (!is_null($data['categories'])) {
                $newCategories = $this->getHelper->toCategories($data['categories']);
                $post->replaceCategories($newCategories);
            }

            if (isset($data['file']) && !is_null($data['file'])) {
                $file = $this->uploader->upload($data['file']);
                $post->setFile($file['name']);
                $post->setFileType($file['type']);
            }

            $entityManager->flush();
        }

        return $post;
    }

    /**
     * Creates new record into categories table
     *
     * @param array $data
     * @return Category|ConstraintViolationList
     */
    public function createCategory(array $data)
    {
        $entityManager = $this->doctrine->getManager();
        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description']);

        $errors = $this->validator->validate($category);

        if (count($errors) > 0) {
            return $errors;
        }

        $entityManager->persist($category);
        $entityManager->flush();
        return $category;
    }

    /**
     * Updates exist record into categories table
     *
     * @param $id
     * @param array $data
     * @return Category|ConstraintViolationList
     */
    public function updateCategory($id, array $data)
    {
        $entityManager = $this->doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (is_null($category)) {
            return null;
        }
        $category->setName($data['name']);
        $category->setDescription($data['description']);

        $errors = $this->validator->validate($category);

        if (count($errors) > 0) {
            return $errors;
        }

        $entityManager->flush();


        return $category;
    }

    /**
     * Creates new record into comments table
     * @param array $data
     * @return Comment
     */
    public function createComment(array $data): Comment
    {
        $entityManager = $this->doctrine->getManager();
        $comment = new Comment();
        $comment->setAuthor($data['author']);
        $comment->setContent($data['content']);
        if (isset($data['post_id']) && !is_null($data['post_id'])) {
            $comment->setPost($this->getHelper->getPost($data['post_id']));
        }
        if (isset($data['category_id']) && !is_null($data['category_id'])) {
            $comment->setCategory($this->getHelper->getCategory($data['category_id']));
        }
        $entityManager->persist($comment);
        $entityManager->flush();
        return $comment;
    }
}