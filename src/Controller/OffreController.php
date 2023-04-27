<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Offre;
use App\Form\OffreType;

use App\Form\EmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\OffreEntityRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\QueryBuilder; 
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\WriterInterface;
use Endroid\QrCode\Writer\StringWriter;
use App\Services\QrcodeService;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/offre')]
class OffreController extends AbstractController
{
    
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/of', name: 'of', methods: ['GET'])]
    public function of(OffreRepository $offreRepository, Request $request,PaginatorInterface $paginator): Response
    {    $offres = $offreRepository->findAll();
            
        $offres = $paginator->paginate(
                $offres, /* query NOT result */
                $request->query->getInt('page', 1),
                3
            );
            

        return $this->render('offre/FrontOffre.html.twig', [
            'offres' => $offres,
        ]);
    }
    #[Route('/{idOffre}/details', name: 'app_offre_details', methods: ['GET'])]
    public function details(Offre $offre, OffreRepository $offreRepository,QrcodeService $qrcodeService): Response
    {
        // Récupérer les offres similaires
        $offresSimilaires = $offreRepository->findSimilaires($offre);
        
        return $this->render('offre/details.html.twig', [
            'offre' => $offre,
            'offresSimilaires' => $offresSimilaires,
            
        ]);
    }
    #[Route('/{idOffre}/participation', name: 'app_participer')]
    public function participer(Offre $offre, OffreRepository $offreRepository,QrcodeService $qrcodeService)
    {
        // Générer un QR code pour confirmer la participation à l'offre avec l'identifiant $id
                $qrcodes = [];
        
            $qrcodeDataUri = $qrcodeService->qrcode($offre->getIdOffre());
            $qrcodes[$offre->getIdOffre()] = $qrcodeDataUri;

        return $this->render('offre/ParcticipationConfirmation.html.twig', [
            'offre' => $offre,
            'qrcodes' => $qrcodes,
        ]);
    }
   /* 
    #[Route('/{idOffre}/mailing', name: 'app_participer')]
    public function participer(Request $request,Offre $offre,MailerInterface $mailer): Response
    {
        // Créer le contenu de l'email
   // $qrCodeUrl = $this->generateUrl('offre_qrcode', ['idOffre' => $offre->getIdOffre()], UrlGeneratorInterface::ABSOLUTE_URL);
    $emailBody = $this->renderView('offre/mailingOffre.html.twig', [
        'offre' => $offre,
       // 'qrCodeUrl' => $qrCodeUrl,
    ]);

    // Créer l'objet Email
    $email = (new Email())
        ->from('oussema.mahjoubi@esprit.tn')
        ->to('mahjoubioussema928@gmail.com')
        ->subject('Confirmation de participation à l\'offre')
        ->html($emailBody);
        //->attachFromPath('/path/to/qrcode.png');

    // Envoyer l'email
    $mailer->send($email);

    return $this->redirectToRoute('of');

    }
*/    
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,QrcodeService $qrcodeService): Response
    {
        $offres = $entityManager
        ->getRepository(Offre::class)
        ->findAll();

        $qrcodes = [];
        foreach ($offres as $offre) {
            $qrcodeDataUri = $qrcodeService->qrcode($offre->getIdOffre());
            $qrcodes[$offre->getIdOffre()] = $qrcodeDataUri;
        }

    return $this->render('offre/index.html.twig', [
        'offres' => $offres,
            'qrcodes' => $qrcodes,
    ]);

    }
    #[Route('/calendrier', name: 'calendrier', methods: ['GET'])]
    public function calendrier(OffreRepository $offreRepository){

        $offres=$offreRepository->findAll();
        $rdvs= [];
        srand(5);
        foreach ($offres as $offre){
             // generate random time
             $int= mt_rand(1262055681,1267094621);
             $string = date("H:00:00",$int);
 
             // get date value from form 
 

             //dd($dateResultat,$newDate,$newDateResultat);
             ///tableau rdv va contenir l'id de l'envent, som nom et sa description ainsi que sa date 
            $rdvs[]=[
                'idoffre'=>$offre->getIdOffre(),
                'start'=>$offre->getDateDebut()->format('Y-m-d'),
                'end'=>$offre->getDateFin()->format('Y-m-d'),
                'title'=>$offre->getnomSponsor(),
                'description'=> $offre->getdescriptionSponsor(),
                'remise'=> $offre->getRemise(),
                'backgroundColor'=>'#FFFFFF',
                'borderColor'=>'#839c49',
                'textColor'=>'#000000',
                'editable'=>true,
            ];

        }
        //dd($rdvs);
        $data=json_encode($rdvs);
        //dd($data);
        return $this->render('offre/calendrier.html.twig',compact('data'));
    }
    #[Route('/rechercheoffre', name: 'app_offre_recherche')]
    public function rechercheOffre(OffreRepository $offreRepository, Request  $request): Response
    {
        $data=$request->get('search');
        $offre = $offreRepository->searchQB($data);
        
        return $this->render('offre/index.html.twig',
            ["offres" => $offre]);
    }
    #[Route('/afficheoffrepardate',name:'app_offre_date')]
    public function afficherStudentsParDate(OffreRepository $repo)
    {
        $offre=$repo->orderByDateQB();
        return $this->render('offre/index.html.twig',["offres" => $offre]);
    }
    #[Route('/afficheoffrepardatefin',name:'app_offre_date_fin')]
    public function afficherStudentsParDateFin(OffreRepository $repo)
    {
        $offre=$repo->orderByDateQB1();
        return $this->render('offre/index.html.twig',["offres" => $offre]);
    }
    #[Route('/afficheoffreparremise',name:'app_offre_remise')]
    public function afficherStudentsParRemise(OffreRepository $repo)
    {
        $offre=$repo->orderByDateQB2();
        return $this->render('offre/index.html.twig',["offres" => $offre]);
    }
    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer): Response
    {   

        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
      /*  $emailBody = $this->renderView('offre/mailingOffre.html.twig', [
            'offre' => $offre,
           // 'qrCodeUrl' => $qrCodeUrl,
        ]);*/

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();
            $email = (new Email())
            ->from('mahjoubioussema928@gmail.com')
            ->to('oussema.mahjoubi@esprit.tn')
            ->subject('Confirmation de participation à l\'offre')
            ->html('<p>See Twig integration for better HTML integration!</p>');
            //->attachFromPath('/path/to/qrcode.png');
            
    // Envoyer l'email
    $mailer->send($email);

    

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idOffre}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{idOffre}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{idOffre}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    
    

    
   
}
