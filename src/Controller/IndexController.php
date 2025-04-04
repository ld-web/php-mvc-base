<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "homepage")]
  public function home(): string
  {
    return $this->twig->render('index.html.twig');
  }

  #[Route("/login", name: "login", httpMethods: ["GET", "POST"])]
  public function login(): string
  {
    $method = $_SERVER['REQUEST_METHOD'];
    return $this->twig->render('login.html.twig', ['method' => $method]);
  }
}
