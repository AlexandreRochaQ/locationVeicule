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
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
    #[Route('/vehicules', name: 'vehicules')]
    public function indexvehicule(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findBy(['statut' => 'disponible']),
        ]);
    }
    #[Route('/vehicule/{id}/reserver', name: 'reservation')]
    public function reserver(Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $reservation->setUtilisateur($this->getUser());
        $reservation->setVehicule($vehicule);
        $reservation->setDateDebut(new \DateTimeImmutable());
        $reservation->setDateFin((new \DateTimeImmutable())->modify('+3 days'));
        $reservation->setPrixTotal($vehicule->getPrixJournalier() * 3);
        $reservation->setStatut('confirmée');

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('reservations');
    }
}
