<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients')]
final class ClientController extends AbstractController
{

    #[Route('/', name: 'client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $utilisateur = $this->getUser(); // récupère l'utilisateur connecté

        $clients = $clientRepository->findBy([
            'utilisateurOwner' => $utilisateur
        ]);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

#[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $client = new Client();
    $form = $this->createForm(ClientType::class, $client);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var \App\Entity\Utilisateur $utilisateur */
        $utilisateur = $this->getUser();
        
        $client->setUtilisateurOwner($utilisateur);
        $client->setGerantNom($utilisateur->getNom());
        $client->setGerantPrenom($utilisateur->getPrenom());

        $em->persist($client);
        $em->flush();

        return $this->redirectToRoute('client_index');
    }

    return $this->render('client/new.html.twig', [
        'form' => $form->createView(),
    ]);
}



    #[Route('/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $em): Response
    {
        // Empêcher l'accès si le client n'appartient pas à l'utilisateur connecté
        if ($client->getUtilisateurOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Ce client ne vous appartient pas.');
        }

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }
    #[Route('/{id}', name: 'client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $em): Response
    {
        if ($client->getUtilisateurOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Ce client ne vous appartient pas.');
        }

        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
public function show(Client $client): Response
{
    if ($client->getUtilisateurOwner() !== $this->getUser()) {
        throw $this->createAccessDeniedException('Ce client ne vous appartient pas.');
    }

    return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }



}
