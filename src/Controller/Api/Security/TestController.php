<?php

namespace App\Controller\Api\Security;

use App\Controller\AbstractController;
use App\Routing\Attribute\Route;

class TestController extends AbstractController
{
  #[Route("/api/security/test", name: "api_security_test")]
  public function test(): string
  {
    header('Content-type: application/json; charset=utf-8');
    return json_encode(['message' => "hello"]);
  }
}
