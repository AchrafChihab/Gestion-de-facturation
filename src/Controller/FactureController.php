<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Statue;
use App\Entity\Clients;
use App\Entity\Facture;
use App\Entity\Service;
use App\Entity\Commande;
use App\Form\FactureType;
use App\Entity\Lignefacture;
use App\Services\MailerService;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\StatueRepository;
use App\Repository\ClientsRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    
    /**
     * @Route("/", name="facture_index", methods={"GET"})
     */
    public function index(FactureRepository $factureRepository): Response
    {
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->getAllByDesc(),
        ]);
    }
    /**
     * @Route("/{id}/facture", name="listefactureclient", methods={"GET", "POST"})
     */
    public function listefactureclient(Request $request,EntityManagerInterface $entityManager,ClientsRepository $ClientRepository,FactureRepository $factureRepository,$id): Response
    { 
        $client_selected = $ClientRepository->findOneBy(['id'=>$id]);
        
        if($client_selected){
            $client_select = $entityManager->getRepository(Clients::class)->find($client_selected);            
            $liste_facture = $entityManager->getRepository(Facture::class)->Findbyone($id);
            
            return $this->renderForm('facture/listefacture.html.twig', [
                'client_selected' => $client_selected,
                'liste_facture' => $liste_facture,
            ]);
          
        }
        else{
            echo "Client non exist";
            exit();
        }        
    }
    
    /**
     * @Route("/new", name="facture_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $client_id =  $request->query->get('client','');
        if($client_id){
            $client_selected = $entityManager->getRepository(Clients::class)->find($client_id);
            if($client_selected){
                $facture->setClient($client_selected);
            }
        }
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($client_id && $form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('listefactureclient', [
                'id' => $client_id  
            ], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->renderForm('facture/new.html.twig', [
                'facture' => $facture,
                'form' => $form,
            ]);
        }
    }

        /**
     * @Route("/dupliquer_facture/{id}", name="dupliquer_facture", methods={"GET", "POST"})
    */
    public function dupliquerdevis(Request $request, EntityManagerInterface $entityManager,Facture $facture ): Response
    {        
        $new_devis =  $facture->dupliquer_facture();  
     
        $form = $this->createForm(FactureType::class, $new_devis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($new_devis);
            $entityManager->flush();
            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('facture/dupliquer.html.twig', [
            'devi' => $new_devis,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="facture_show", methods={"GET"})
     */
    public function show(Request $request,EntityManagerInterface $entityManager,Facture $facture,ClientsRepository $ClientRepository,FactureRepository $FactureRepository,$id): Response
    {         
        $facture = $FactureRepository->findOneBy(['id'=>$id]);
        if($facture){ 
            return $this->render('facture/show.html.twig', [
                'facture' => $facture
            ]);
        }
        echo "non exist";
        exit();    
    }

    /**
     * @Route("/{id}/edit", name="facture_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{id}", name="facture_delete", methods={"POST"})
     */
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('facture_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/{id}/download", name="download_file", methods={"GET"})
     */
    public function downloadFileAction(Request $request,EntityManagerInterface $entityManager,Facture $facture,ClientsRepository $ClientRepository,FactureRepository $FactureRepository,$id): Response
    { 
        $facture = $FactureRepository->findOneBy(['id'=>$id]);

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

        $html = $this->renderView('facture/imprimer.html.twig', [
                    'facture' => $facture
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

        return new Response($facture);
    }
    
    /**
     * @Route("/{id}/mail/", name="sendmail", methods={"GET"})
     */
    public function contact(MailerInterface $mailer,Request $request,FactureRepository $FactureRepository,EntityManagerInterface $entityManager,$id): Response
    {       
        $facture = $FactureRepository->findOneBy(['id'=>$id]);
        $mail_id =  $request->query->get('mail','');
        $email = (new TemplatedEmail())
            ->from('alfalil.test@gmail.com')
            ->to($mail_id)
            ->subject('facture')
            ->htmlTemplate('facture/contact.html.twig')
            ->context([
                'facture'=> $facture
            ]);
        $mailer->send($email);
        // return new Response();  
        return $this->redirectToRoute('facture_show', [
            'id' => $facture->getId(),'sts'=>'done',
        ], Response::HTTP_SEE_OTHER); 
                   
    }

        /**
     * @Route("/{id}/convertfacture/", name="convertfacture", methods={"GET", "POST"})
     */
    public function convertfacture($id,ManagerRegistry $doctrine,Request $request, EntityManagerInterface $entityManager)
    {      
        $em = $doctrine->getManager();        
        $idclient =  $request->query->get('idclient');
        $facture_selected =  $entityManager->getRepository(Commande::class)->findOneBy(array('id' => $id));         
        $cloneorder = $facture_selected->convertfacture();  
        $em->persist($cloneorder);
        $em->flush(); 
        return $this->redirectToRoute('facture_show', ['id'=>$cloneorder->getId()]); 
 
    }



}
