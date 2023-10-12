<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/api", name="home")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->json([
            'message' => 'You successfully authenticated!',
            'email' => $user->getEmail(),
        ]);
        // return new Response('Hello, this is a Symfony app!');
    }
}
