<?php

namespace App\Controller;

use DateTime;
use App\Entity\Devis;
use App\Entity\Facture;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\LigneCommande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->getAllByDesc(),
        ]);
    }

     /**
     * @Route("/{id}/download", name="telecharger_commande_pdf", methods={"GET"})
     */
    public function telechargerdevispdf(Request $request,EntityManagerInterface $entityManager,CommandeRepository $CommandeRepository,$id): Response
    { 
        $commande = $CommandeRepository->findOneBy(['id'=>$id]);

        $pdfoption = new Options();
        
        
        $pdfoption->set('Defaultfont','Arial');
        $pdfoption->setIsRemoteEnabled(true);
        
        
        $dompdf = new Dompdf($pdfoption);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ]);
        $dompdf ->setHttpContext($context);

        $html = $this->renderView('devis/imprimer.html.twig', [
                    'commande' => $commande
                ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

        return new Response($commande);
    }

    /**
     * @Route("/{id}/commande/", name="convertcommande", methods={"GET", "POST"})
     */
    public function commande($id,ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager)
    {      
        $em = $doctrine->getManager();
        $idclient =  $request->query->get('idclient');
        $facture_selected =  $entityManager->getRepository(Devis::class)->findOneBy(array('id' => $id));
         
        $cloneorder = $facture_selected->convertcommande();  
        $em->persist($cloneorder);
        $em->flush(); 
        return $this->redirectToRoute('commande_show', ['id'=>$cloneorder->getId()]); 
 
    }

    /**
     * @Route("/new", name="commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

        /**
     * @Route("/dupliquer_commande/{id}", name="dupliquer_commande", methods={"GET", "POST"})
    */
    public function dupliquercommande(Request $request, EntityManagerInterface $entityManager,Commande $commande): Response
    {        
        $new_commande =  $commande->dupliquer_commande();
        $form = $this->createForm(CommandeType::class, $new_commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($new_commande);
            $entityManager->flush();
            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('commande/dupliquer.html.twig', [
            'commande' => $new_commande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        dump($commande);
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index', [], Response::HTTP_SEE_OTHER);
    }

    
}
