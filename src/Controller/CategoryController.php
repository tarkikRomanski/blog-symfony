<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends Controller
{
    /**
     * @Route("/", name="category")
     */
    public function index()
    {
        return $this->render('category/index.html.twig');
    }
}
