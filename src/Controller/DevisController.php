<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Devis;
use App\Entity\Clients;
use App\Form\DevisType;
use App\Entity\Lignedevis;
use App\Repository\DevisRepository;
use App\Repository\ClientsRepository;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/devis")
*/
class DevisController extends AbstractController
{
    /**
     * @Route("/", name="devis_index", methods={"GET"})
    */
    public function index(DevisRepository $devisRepository): Response
    {
        return $this->render('devis/index.html.twig', [
            'devis' => $devisRepository->getDevis('expedier'),
        ]);
    }
    /**
     * @Route("/liste_a_voir", name="listeavoir", methods={"GET"})
    */
    public function listeavoir(DevisRepository $DevisRepository): Response
    {
        $devis = $DevisRepository->getDevisAnnuler('expedier');

        return $this->render('devis/listeavoir.html.twig', [
            'devis' => $devis
        ]);
    }

    /**
     * @Route("/{id}/devis", name="listedevisclient", methods={"GET", "POST"})
    */
    public function listedevisclient(Request $request,EntityManagerInterface $entityManager,ClientsRepository $ClientRepository,DevisRepository $devisRepository,$id): Response
    { 
        $client_selected = $ClientRepository->findOneBy(['id'=>$id]);
        
        if($client_selected){
            $client_select = $entityManager->getRepository(Clients::class)->find($client_selected);            
            $liste_devis = $entityManager->getRepository(Devis::class)->find($id);
            
            return $this->renderForm('devis/listedevis.html.twig', [
                'client_selected' => $client_selected,
                'liste_devis' => $liste_devis,
            ]);
          
        }
        else{
            echo "Client non exist";
            exit();
        }        
    }

     /**
     * @Route("/{id}/download", name="telecharger_devis_pdf", methods={"GET"})
     */
    public function telechargerdevispdf(Request $request,EntityManagerInterface $entityManager,Devis $devis,ClientsRepository $ClientRepository,DevisRepository $DevisRepository,$id): Response
    { 
        $devis = $DevisRepository->findOneBy(['id'=>$id]);

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
                    'devis' => $devis
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

        return new Response($devis);
    }

    /**
     * @Route("/new", name="devis_new", methods={"GET", "POST"})
    */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devi = new Devis();
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);
           
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($devi);

            $entityManager->flush();

            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/new.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/dupliquer_devis/{id}", name="dupliquer_devis", methods={"GET", "POST"})
    */
    public function dupliquerdevis(Request $request, EntityManagerInterface $entityManager,Devis $devi ): Response
    {        
        $new_devis =  $devi->depluc();  
     
        $form = $this->createForm(DevisType::class, $new_devis);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($new_devis);
            $entityManager->flush();
            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('devis/dupliquer.html.twig', [
            'devi' => $new_devis,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="devis_show", methods={"GET"})
    */
    public function show(Devis $devi): Response
    {
        return $this->render('devis/show.html.twig', [
            'devi' => $devi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="devis_edit", methods={"GET", "POST"})
    */
    public function edit(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevisType::class, $devi);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('devis/edit.html.twig', [
            'devi' => $devi,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="devis_delete", methods={"POST"})
    */
    public function delete(Request $request, Devis $devi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$devi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($devi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('devis_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/mail/", name="senddevismail", methods={"GET"})
    */
    public function contact(MailerInterface $mailer,Request $request,DevisRepository $DevisRepository,EntityManagerInterface $entityManager,$id): Response
    {       
        $devis = $DevisRepository->findOneBy(['id'=>$id]);
        $mail_id =  $request->query->get('mail','');
        $email = (new TemplatedEmail())
            ->from('alfalil.test@gmail.com')
            ->to($mail_id)
            ->subject('Devis')
            ->htmlTemplate('devis/contact.html.twig')
            ->context([
                'devis'=> $devis
            ]);
        $mailer->send($email);
        // return new Response();  
        return $this->redirectToRoute('devis_show', [
            'id' => $devis->getId(),'sts'=>'done',
        ], Response::HTTP_SEE_OTHER); 
                   
    }
}
