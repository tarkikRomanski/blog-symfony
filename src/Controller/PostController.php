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

    /**
     * @Route("/{id}", name="post.get")
     * @param string $id
     * @return object|\Symfony\Component\HttpFoundation\Response
     */
    public function get($id)
    {
        return $this->render('post/get.html.twig', ['id' => $id]);
    }

    /**
     * @Route("/edit/{id}", name="post.update")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        return $this->render('post/update.html.twig', ['id' => $id]);
    }
}
