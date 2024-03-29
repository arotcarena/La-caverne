<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(): Response
    {
        return $this->render('view/admin/home/index.html.twig', [
            'current_menu' => 'admin_home'
        ]);
    }
}
