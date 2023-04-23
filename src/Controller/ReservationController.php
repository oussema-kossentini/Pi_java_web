<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;
use Dompdf\Dompdf;
use App\Service\PDFgenerator;
use Knp\Component\Pager\Pagination\SlidingPaginationInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
public function index(Request $request, ReservationRepository $reservationRepository, PaginatorInterface $paginator): Response
{
    $reservation = $paginator->paginate(
        $reservationRepository->findAll(),
        $request->query->getInt('page', 1),
        2
    );

    return $this->render('reservation/index.html.twig', [
        'reservation' => $reservation,
    ]);
}


    #[Route('/generate-pdf', name: 'app_reservation_generate_pdf', methods: ['GET'])]
public function generatePdf(ReservationRepository $reservationRepository): Response
{
    // Récupération des données de la base de données
    $reservations = $reservationRepository->findAll();

    $reservationData = [];

    foreach ($reservations as $reservation) {
        $reservationData[] = [
            'id' => $reservation->getId(),
            'cin' => $reservation->getCin(),
            'nom' => $reservation->getNom(),
            'prenom' => $reservation->getPrenom(),
            'ville' => $reservation->getVille(),
            'num_tel' => $reservation->getNumtel(),
            'adresse_m' => $reservation->getAdressem(),
            'date_livraison' => $reservation->getDatelivraison(),
            'type_produit' => $reservation->getTypeproduit(),
            'lieu_depart' => $reservation->getLieudepart(),
            'lieu_arrivee' => $reservation->getLieuarrivee(),
            'poids' => $reservation->getPoids()
            
            // Ajouter d'autres propriétés si nécessaire
        ];
    }

    $html = $this->renderView('reservation/generatepdf.html.twig', [
        'reservationData' => $reservationData,
    ]);

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfContent = $dompdf->output();

    $response = new Response();
    $response->setContent($pdfContent);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment;filename=reservation.pdf');

    return $response;
}

   
    
    
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation, FlashyNotifier $flashy): Response
    {   
        // ...
        
        $flashy->success('Voici la reservation que vous voulez voir  !');
        
        // ...
        
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
    
    

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, FlashyNotifier $flashy): Response
{
    $form = $this->createForm(ReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reservationRepository->save($reservation, true);

        $flashy->success('Réservation modifiée avec succès !');

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservation/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, FlashyNotifier $flashy): Response
{
    if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
        
        // Supprime la réservation
        $reservationRepository->remove($reservation, true);

        // Affiche un message de confirmation
        $flashy->warning('La réservation a été supprimée avec succès !');
    }

    return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
}
   
}
