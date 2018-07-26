<?php

namespace App\Service;


use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;

class Helper
{
    private $doctrine;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Helper constructor.
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

        if($onlyId) {
            return new ArrayCollection($categoriesIdList);
        }



        $categoriesList = new ArrayCollection();
        foreach ($categoriesIdList as $categoryId) {
            $category = $this->doctrine
                ->getRepository(Category::class)
                ->find($categoryId);
            if (!is_null($category) && !$categoriesList->contains($category)) {
                $categoriesList->add($category);
            }
        }

        return $categoriesList;
    }
}