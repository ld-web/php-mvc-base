<?php

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Routing\Attribute\Route;
use PDO;

class UserController extends AbstractController
{
  #[Route('/api/users', name: 'api_users')]
  public function index(PDO $pdo)
  {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
  }
}
