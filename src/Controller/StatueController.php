<?php

namespace App\Controller;

use App\Entity\Statue;
use App\Form\StatueType;
use App\Repository\StatueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/statue")
 */
class StatueController extends AbstractController
{
    /**
     * @Route("/", name="statue_index", methods={"GET"})
     */
    public function index(StatueRepository $statueRepository): Response
    {
        return $this->render('statue/index.html.twig', [
            'statues' => $statueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="statue_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statue = new Statue();
        $form = $this->createForm(StatueType::class, $statue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statue);
            $entityManager->flush();

            return $this->redirectToRoute('statue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('statue/new.html.twig', [
            'statue' => $statue,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="statue_show", methods={"GET"})
     */
    public function show(Statue $statue): Response
    {
        return $this->render('statue/show.html.twig', [
            'statue' => $statue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="statue_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Statue $statue, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatueType::class, $statue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('statue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('statue/edit.html.twig', [
            'statue' => $statue,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="statue_delete", methods={"POST"})
     */
    public function delete(Request $request, Statue $statue, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statue->getId(), $request->request->get('_token'))) {
            $entityManager->remove($statue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('statue_index', [], Response::HTTP_SEE_OTHER);
    }
}
