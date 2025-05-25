<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Client;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/factures')]
class FactureController extends AbstractController
{
    #[Route('/client/{id}', name: 'facture_client', methods: ['GET'])]
    public function index(Client $client, FactureRepository $factureRepository): Response
    {
        if ($client->getUtilisateurOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('facture/index.html.twig', [
            'client' => $client,
            'factures' => $factureRepository->findBy(['client' => $client]),
        ]);
    }

    #[Route('/all', name: 'facture_all', methods: ['GET'])]
    public function allForCurrentUser(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $factures = $em->getRepository(Facture::class)->createQueryBuilder('f')
            ->join('f.client', 'c')
            ->where('c.utilisateurOwner = :user')
            ->setParameter('user', $user)
            ->orderBy('f.dateFacture', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('facture/all.html.twig', [
            'factures' => $factures
        ]);
    }


#[Route('/client/{id}/new', name: 'facture_new', methods: ['GET', 'POST'])]
public function new(Client $client, Request $request, EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('FACTURE_CREATE', $client);

    $facture = new Facture();
    $facture->setClient($client);
    $facture->setDateFacture((new \DateTime())->setTime(0, 0));

    $latestId = $em->getRepository(Facture::class)->findOneBy([], ['id' => 'DESC']);
    $nextId = $latestId ? $latestId->getId() + 1 : 1;
    $facture->setNumFacture('FAC' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT));

    $form = $this->createForm(FactureType::class, $facture);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($facture);
        $em->flush();

        $this->addFlash('success', 'La facture a été ajoutée avec succès.');
        return $this->redirectToRoute('facture_all');

    }

    return $this->render('facture/new.html.twig', [
        'form' => $form->createView(),
        'client' => $client,
    ]);
}


    #[Route('/{id}/edit', name: 'facture_edit', methods: ['GET', 'POST'])]
    // #[IsGranted('FACTURE_EDIT', 'facture')]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('FACTURE_EDIT', $facture);

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('facture_client', ['id' => $facture->getClient()->getId()]);
        }

        return $this->render('facture/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $facture->getClient(),
            'facture' => $facture
        ]);
    }



#[Route('/{id}/delete', name: 'facture_delete', methods: ['POST'])]
// $this->denyAccessUnlessGranted('FACTURE_DELETE', $facture);
public function delete(Request $request, Facture $facture, EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('FACTURE_DELETE', $facture);

    if ($this->isCsrfTokenValid('delete_facture_' . $facture->getId(), $request->request->get('_token'))) {
        $em->remove($facture);
        $em->flush();
    }

    return $this->redirectToRoute('facture_client', ['id' => $facture->getClient()->getId()]);
}

}
