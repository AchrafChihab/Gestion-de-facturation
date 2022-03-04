<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Clients;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Repository\ClientsRepository;
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
            'devis' => $devisRepository->findAll(),
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
