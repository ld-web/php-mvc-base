<?php

namespace App\Controller;

use App\Routing\Attribute\Route;
use PDO;

class UserController extends AbstractController
{
  // D'abord la route la plus spécifique
  #[Route('/user/{id}/{slug}', name: "user_item")]
  public function multipleUrlParams(int $id, string $slug, PDO $pdo): string
  {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->execute(['id' => $id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $this->twig->render('user.html.twig', ['user' => $user, 'slug' => $slug]);
  }

  // Ensuite les routes plus générales
  #[Route('/user/{id}', name: "user_item")]
  public function item(int $id, PDO $pdo): string
  {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->execute(['id' => $id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $this->twig->render('user.html.twig', ['user' => $user]);
  }
}
