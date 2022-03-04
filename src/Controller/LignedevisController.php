<?php

namespace App\Controller;

use App\Entity\Lignedevis;
use App\Form\LignedevisType;
use App\Repository\LignedevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lignedevis")
 */
class LignedevisController extends AbstractController
{
    /**
     * @Route("/", name="lignedevis_index", methods={"GET"})
     */
    public function index(LignedevisRepository $lignedevisRepository): Response
    {
        return $this->render('lignedevis/index.html.twig', [
            'lignedevis' => $lignedevisRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lignedevis_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lignedevi = new Lignedevis();
        $form = $this->createForm(LignedevisType::class, $lignedevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lignedevi);
            $entityManager->flush();

            return $this->redirectToRoute('lignedevis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignedevis/new.html.twig', [
            'lignedevi' => $lignedevi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lignedevis_show", methods={"GET"})
     */
    public function show(Lignedevis $lignedevi): Response
    {
        return $this->render('lignedevis/show.html.twig', [
            'lignedevi' => $lignedevi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lignedevis_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lignedevis $lignedevi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LignedevisType::class, $lignedevi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('lignedevis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lignedevis/edit.html.twig', [
            'lignedevi' => $lignedevi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="lignedevis_delete", methods={"POST"})
     */
    public function delete(Request $request, Lignedevis $lignedevi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lignedevi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lignedevi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lignedevis_index', [], Response::HTTP_SEE_OTHER);
    }
}
