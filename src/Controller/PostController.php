<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/posts")
 */
class PostController extends Controller
{
    /**
     * @Route("/create", name="post.create")
     */
    public function create()
    {
        return $this->render('post/create.html.twig');
    }
}
