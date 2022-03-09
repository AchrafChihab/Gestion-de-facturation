<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Facture;
use App\Form\ClientsType;
use App\Repository\DevisRepository;
use App\Repository\ClientsRepository;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LignefactureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/clients")
 */
class ClientsController extends AbstractController
{
    /**
     * @Route("/dashbord", name="dashbord", methods={"GET"})
     */
    public function dashbord(CommandeRepository $commandeRepository, LignefactureRepository $lignefactureRepository,FactureRepository $factureRepository,ClientsRepository $clientsRepository): Response
    {
        $totalrevenue = $lignefactureRepository->Sommeligne();
        $client = $clientsRepository->findAll();        
        $clientnumber = count($client);
        return $this->render('clients/dashbord.html.twig', [
            'clients'       => $clientsRepository->findAll(),
            'factures'      => $factureRepository->findAll(),
            'commande'      => $commandeRepository->findAll(),
            'clientnumber'  =>$clientnumber,
            'totalrevenue'  => $totalrevenue
        ]);
    }
    /**
     * @Route("/client", name="clients_index", methods={"GET"})
     */
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="clients_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clients/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="clients_show", methods={"GET"})
     */
    public function show($id,CommandeRepository $commandeRepository, DevisRepository $devisRepository , LignefactureRepository $lignefactureRepository,FactureRepository $factureRepository,Clients $client): Response
    {
        $factures = $factureRepository->Findbyone($id);
        $commande = $commandeRepository->Findbyone($id);
        $devis = $devisRepository->Findbyone($id);
        $facturepayer = $factureRepository->getCountFactureBy($id,'valider');
        $totalfactureclient = $lignefactureRepository->SommeTotallignefactureDuClient($id);
        $clientnumber = count($factures);
        return $this->render('clients/show.html.twig', [
            'client' => $client,
            'factures' => $factures,
            'commande' => $commande,
            'devis' => $devis,
            'clientnumber' => $clientnumber,
            'facturepayer' => $facturepayer,
            'totalfactureclient' => $totalfactureclient,
        ]);
    }
    
    /**
     * @Route("/{id}/edit", name="clients_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="clients_delete", methods={"POST"})
     */
    public function delete(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clients_index', [], Response::HTTP_SEE_OTHER);
    }
}
