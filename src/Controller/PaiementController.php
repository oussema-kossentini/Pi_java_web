<?php

namespace App\Controller;
use App\Form\SmsType;
use App\Service\TwilioClient;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use App\Entity\Paiement;
use App\Form\PaiementType;



#[Route('/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paiements = $entityManager
            ->getRepository(Paiement::class)
            ->findAll();

        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/front.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/paiement/sendsms', name: 'Password_send_sms')]
    // Send SMS notification to admin
    public function sendSms(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SmsType::class);

        $form->handleRequest($request);
        $err = " ";
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $num = $data['number'];
            $accountSid = 'AC7a8360da7ec31794623bf07ba4b2c005';
            $authToken = '4ff5a9c314ecdfc1503f4182dae05ba1';
            $client = new Client($accountSid, $authToken);
            $message = $client->messages->create(
                $num, // replace with admin's phone number
                [
                    'from' => '+15672922329', // replace with your Twilio phone number
                    'body' => 'Bonjour cher client, votre paiement est en cours de traitement. Merci pour votre confiance !', // replace with your message
                ]
            );
            return $this->redirectToRoute('app_paiement_new');
        } else {
            $err = "erreur";
        }

        return $this->renderForm('paiement/index2.html.twig', [

            'form' => $form,
            'err' => $err,
        ]);
    }
}
