<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{   
    #[Route('/', name: 'home')]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'vehicules' => $vehiculeRepository->findBy(['statut' => 'disponible']),
        ]);
    }
    #[Route('/vehicules', name: 'vehicules')]
    public function indexvehicule(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findBy(['statut' => 'disponible']),
        ]);
    }
    // #[Route('/reservations', name: 'reservations')]
    // public function indexreservations(VehiculeRepository $vehiculeRepository): Response
    // {
    //     return $this->render('vehicule/index.html.twig', [
    //     ]);
    // }
}
