<?php

namespace App\Service;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use Psr\Container\ContainerInterface;

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
     * SetHelper constructor.
     * @param ContainerInterface $container
     * @param GetHelper $getHelper
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container, GetHelper $getHelper)
    {
        $this->container = $container;
        $this->getHelper = $getHelper;

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

        if (!is_null($data['categories'])) {
            $newCategories = $this->getHelper->toCategories($data['categories'])->toArray();
            foreach ($newCategories as $category) {
                $post->addCategory($category);
            }
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
        if(!is_null($post)) {
            $post->setName($data['name']);
            $post->setContent($data['content']);

            if (!is_null($data['categories'])) {
                $newCategories = $this->getHelper->toCategories($data['categories']);
                $post->replaceCategories($newCategories);
            }
            $entityManager->flush();
        }

        return $post;
    }

    /**
     * Creates new record into categories table
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        $entityManager = $this->doctrine->getManager();
        $category = new Category();
        $category->setName($data['name']);
        $category->setDescription($data['description']);
        $entityManager->persist($category);
        $entityManager->flush();
        return $category;
    }

    /**
     * Updates exist record into categories table
     *
     * @param $id
     * @param array $data
     * @return Category
     */
    public function updateCategory($id, array $data): Category
    {
        $entityManager = $this->doctrine->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);
        if (!is_null($category)) {
            $category->setName($data['name']);
            $category->setDescription($data['description']);
            $entityManager->flush();
        }

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
        $comment->setPost($this->getHelper->getPost($data['post_id']));
        $comment->setCategory($this->getHelper->getCategory($data['category_id']));
        $entityManager->persist($comment);
        $entityManager->flush();
        return $comment;
    }
}