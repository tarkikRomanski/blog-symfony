<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CategoryController
 * @package App\Controller
 * @Route("/categories")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="category")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('category/index.html.twig');
    }

    /**
     * @Route("/edit/{id}", name="category.update")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        return $this->render('category/update.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/create", name="category.create")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
        return $this->render('category/create.html.twig');
    }

    /**
     * @Route("/{id}", name="category.get")
     * @param string $id
     * @return object|\Symfony\Component\HttpFoundation\Response
     */
    public function get($id)
    {
        return $this->render('category/get.html.twig', ['id' => $id]);
    }
}
