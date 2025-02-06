<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function adminDashboard(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
}
