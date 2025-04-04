<?php

namespace App\Controller;

use App\Routing\Attribute\Route;

class ContactController
{
  #[Route("/contact", name: "contact_page")]
  public function contact(): string
  {
    return "Page de contact";
  }

  #[Route("/devis", name: "page_devis")]
  public function devis(): string
  {
    return "Page de devis";
  }
}
