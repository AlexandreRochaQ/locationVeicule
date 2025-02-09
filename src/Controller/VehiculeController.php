<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Reservation;
use App\Entity\Vehicule;
use App\Form\CommentaireType;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/vehicule')]
final class VehiculeController extends AbstractController
{
    #[Route(name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
    
            if ($photo) {
                $newFilename = $slugger->slug($vehicule->getMarque()) . '-' . uniqid() . '.' . $photo->guessExtension();
    
                $photo->move(
                    $this->getParameter('photos_directory'),
                    $newFilename
                );
    
                $vehicule->setPhoto($newFilename);
            }
    
            $entityManager->persist($vehicule);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET', 'POST'])]
    public function show(Vehicule $vehicule, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $commentaires = $vehicule->getCommentaires();
        $peutCommenter = false;

        if ($user) {
            $peutCommenter = $entityManager->getRepository(Reservation::class)->findOneBy([
                'utilisateur' => $user,
                'vehicule' => $vehicule
            ]) !== null;
        }

        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setVehicule($vehicule);
            $commentaire->setUtilisateur($user);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_show', ['id' => $vehicule->getId()]);
        }

        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
            'commentaires' => $commentaires,
            'form' => $peutCommenter ? $form->createView() : null
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/vehicule/{id}/commentaire', name: 'ajouter_commentaire')]
public function ajouterCommentaire(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
{
    $commentaire = new Commentaire();
    $commentaire->setUtilisateur($this->getUser());
    $commentaire->setVehicule($vehicule);

    $form = $this->createFormBuilder($commentaire)
        ->add('note', IntegerType::class)
        ->add('message', TextareaType::class)
        ->add('submit', SubmitType::class, ['label' => 'Laisser un commentaire'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($commentaire);
        $entityManager->flush();
        return $this->redirectToRoute('vehicules');
    }

    return $this->render('commentaire/ajouter.html.twig', ['form' => $form->createView()]);
}
    #[Route('/{id}', name: 'app_vehicule_show', methods: ['GET'])]
    public function showcomments(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
            'commentaires' => $vehicule->getCommentaires(),
        ]);
    }

    #[Route('/vehicule/{id}/add-favori', name: 'app_vehicule_add_favori', methods: ['GET'])]
    public function addFavori(Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        if ($utilisateur && !$vehicule->getFavoris()->contains($utilisateur)) {
            $vehicule->addFavori($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index');
    }

    #[Route('/vehicule/{id}/remove-favori', name: 'app_vehicule_remove_favori', methods: ['GET'])]
    public function removeFavori(Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        if ($utilisateur && $vehicule->getFavoris()->contains($utilisateur)) {
            $vehicule->removeFavori($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index');
    }   
    #[Route('/vehicule/favoris', name: 'app_vehicule_favoris', methods: ['GET'])]
    public function favoris(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        
        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        $favoris = $entityManager->getRepository(Vehicule::class)
            ->createQueryBuilder('v')
            ->leftJoin('v.favoris', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $utilisateur)
            ->getQuery()
            ->getResult();

        return $this->render('vehicule/favoris.html.twig', [
            'vehicules' => $favoris,
        ]);
    }
}
