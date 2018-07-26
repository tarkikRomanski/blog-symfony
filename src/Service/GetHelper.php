<?php

namespace App\Service;


use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class GetHelper
{
    private $doctrine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * GetHelper constructor.
     * @param ContainerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        if (!$container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".');
        }

        $this->doctrine = $container->get('doctrine');
    }

    /**
     * Returns categories list from string
     *
     * @param null $categories
     * @param bool $onlyId
     * @param string $delimiter
     * @return ArrayCollection
     */
    public function toCategories($categories = null, $onlyId = false, $delimiter = ',')
    {
        if (is_null($categories)) {
            return new ArrayCollection();
        }

        $categoriesIdList = explode($delimiter, $categories);

        if ($onlyId) {
            return new ArrayCollection($categoriesIdList);
        }


        $categoriesList = new ArrayCollection();
        foreach ($categoriesIdList as $categoryId) {
            $category = $this->getCategory($categoryId);
            if (!is_null($category) && !$categoriesList->contains($category)) {
                $categoriesList->add($category);
            }
        }

        return $categoriesList;
    }

    /**
     * Finds category by id
     *
     * @param $id
     * @return Category|null
     */
    public function getCategory($id): ?Category
    {
        return $this->doctrine
            ->getRepository(Category::class)
            ->find($id);
    }

    /**
     * Finds post by id
     *
     * @param $id
     * @return Post|null
     */
    public function getPost($id): ?Post
    {
        return $this->doctrine
            ->getRepository(Post::class)
            ->find($id);
    }

    /**
     * Returns array with errors
     *
     * @param ConstraintViolationList $constraintViolationList
     * @return array
     */
    public function toErrorsArray(ConstraintViolationList $constraintViolationList): array
    {
        $errorsIterator = $constraintViolationList->getIterator();
        $errorsList = [];
        foreach ($errorsIterator as $error) {
            $errorsList[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorsList;
    }
}