<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Symfony\Component\Mailer\Email;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
// use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\FormError;
use Sentiment\Analyzer;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    private $mailer;
    
   
//     public function __construct(MailerInterface $mailer, PaginatorInterface $paginator)
// {
//     $this->mailer = $mailer;
//     $this->paginator = $paginator;
// }



    public function __construct( PaginatorInterface $paginator)
{
    
    $this->paginator = $paginator;
}


    #[Route('/22', name: 'app_reclamation_index1', methods: ['GET'])]
    public function index1(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/basefrontindex.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);

    }   
    #[Route('/27', name: 'app_reclamation_index27', methods: ['GET'])]
    public function index27(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/mailrender.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);

    }   



    // public function complaints(ReclamationRepository $reclamationRepository): JsonResponse
    // {
    //     $livraisonCount = $reclamationRepository->countByType('livraison');
    //     $serviceCount = $reclamationRepository->countByType('service');
    //     $paiementCount = $reclamationRepository->countByType('paiement');
    //     $autreCount = $reclamationRepository->countByType('autre');
    
    //     $data = [
    //         'livraison' => $livraisonCount,
    //         'service' => $serviceCount,
    //         'paiement' => $paiementCount,
    //         'autre' => $autreCount,
    //     ];
    
    //     return new JsonResponse($data);
    // }


//     #[Route('/counts', name: 'app_reclamation_counts', methods: ['GET'])]
//     public function getComplaintCounts(ReclamationRepository $reclamationRepository): JsonResponse
// {
//     $counts = [];
//     $reclamations = $reclamationRepository->findAll();
//     foreach ($reclamations as $reclamation) {
//         $type = $reclamation->getType();
//         if (isset($counts[$type])) {
//             $counts[$type]++;
//         } else {
//             $counts[$type] = 1;
//         }
//     }
//     return $this->json($counts);
// }



    
    #[Route('/statreclamation', name: 'app_reclamation_index277', methods: ['GET'])]
public function index277(ReclamationRepository $reclamationRepository): Response
{

    // // $complaintCounts = $this->getComplaintCounts();





    // $ob = new Highchart();

    // $ob->chart->renderTo('piechart');

    // $ob->title->text('stat type');

    // $ob->plotOptions->pie(array(
    //     'allowPointSelect' => true,
    //     'cursor' => 'pointer',
    //     'dataLabels' => array('enabled' => false),
    //     'showInLegend' => true
    // ));

    // $data2 = [];
    // $stattype = [];
    // $categCount = [];

    // $reclamations = $reclamationRepository->findAll();
    // foreach ($reclamations as $reclamation) {
    //     $stattype[] = $reclamation->getType();
    //     $categCount[] = $reclamationRepository->getproduits($reclamation->getId());
    // }

    // $i = 0;
    // foreach ($stattype as $p) {
    //     if ($i < count($categCount[0])) {
    //         array_push($data2,[$stattype[$i],$categCount[0][$i]['num']]);
    //     } else {
    //         // handle the error, e.g. by skipping this element
    //     }
    //     $i++;
    // }

    // $ob->series(array(
    //     array(
    //         'type' => 'pie',
    //         'name' => 'typeR',
    //         'data' => $data2
    //     )
    // ));
    $query = $reclamationRepository->createQueryBuilder('r')
    ->select('r.type, COUNT(r.id) AS num')
    ->groupBy('r.type')
    ->getQuery();

$results = $query->getResult();
$data = [];
foreach ($results as $result) {
    $data[] = [
        'name' => $result['type'],
        'y' => (int) $result['num']
    ];
}
$ob = new Highchart();
$ob->chart->renderTo('piechart');
$ob->title->text('Statistiques des réclamations');
$ob->plotOptions->pie([
    'allowPointSelect' => true,
    'cursor' => 'pointer',
    'dataLabels' => ['enabled' => false],
    'showInLegend' => true
]);
$ob->series([
    [
        'type' => 'pie',
        'name' => 'Réclamations',
        'data' => $data
    ]
]);

    return $this->render('reclamation/statrec.html.twig', [
        // 'reclamations' => $reclamations,
        'chart' => $ob
    ]);
}

    


 
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(Request $request ,ReclamationRepository $reclamationRepository): Response
    {
        $queryBuilder = $reclamationRepository->createQueryBuilder('r');

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 // nombre d'éléments par page
        );

        return $this->render('reclamation/index.html.twig', [
            'pagination' => $pagination,
            'rec2' => $reclamationRepository->findAll(),
        ]);
    
    }
    
    #[Route('/searchreceajax', name: 'app_searchrec', methods: ['GET'])]
     public function searchajax(Request $request ,ReclamationRepository $repository)
     {
         $requestString=$request->get('searchValue');
         $rec = $repository->findRecbyname($requestString);
         // $tache=$repository->findAll();
 
         return $this->render('reclamation/ajax.html.twig', [
             "pagination"=>$rec,
         ]);
     }

      
    #[Route('/date', name: 'app_rec_date', methods: ['GET'])]
    function decroissantDate(ReclamationRepository $repository){
        $rec = $repository->trie_decroissant_date();
        
        return $this->render('reclamation/index.html.twig',['pagination' => $rec]);
    }

    #[Route('/type', name: 'app_rec_type', methods: ['GET'])]
    function decroissantType(ReclamationRepository $repository){
        $rec = $repository->trie_decroissant_type();
        
        return $this->render('reclamation/index.html.twig',['pagination' => $rec]);
    }
    





    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository, FlashyNotifier $flashy, \Swift_Mailer $mailer): Response
    {        
        // , \Swift_Mailer $mailler
    $reclamation = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);
    $test = false;
    if ($form->isSubmitted() && $form->isValid()) {
        $reclamation->setState("non traitee");
        // Vérifier si la description contient des mots grossiers
            $analyzer = new Analyzer();

            //$a = array("neg => ", "new =>  ", "pos =>  ","compound =>  ");
            $b = $analyzer->getSentiment($reclamation->getDescription());

            $output_text =  $b;


            $json = json_encode($output_text);
            // dd($json);
            
            $negPos = strpos($json, '"neg":');

            if ($negPos !== false) {
                // If the "neg" property exists, extract the value
                $negValue = (float) substr($json, $negPos + 7, 5);
                //dd($post->getAnalysePo());
                
                if ($negValue != 0) {
                    $test = true;
                    return $this->renderForm('reclamation/new.html.twig', [
                        'reclamation' => $reclamation,
                        'form' => $form,
                        'test'=> $test,
                        
                    ]);
                    
                } 
            }

        $reclamationRepository->save($reclamation, true);
        $email = (new \Swift_Message('Reclmataion:' ))
              
        //        ->setFrom('oussema.kossentini@esprit.tn')
        //         ->setTo($reclamation->getEmail())
        //         ->setBody("Mr " . " , merci pour votre reclamation"

        //         );
        // $mailer->send($email);
        ->setFrom('oussema.kossentini@esprit.tn')
                ->setTo($reclamation->getEmail())
                
               // ->setBody("Mr " . " , merci pour votre reclamation "
               ->setBody(
                $this->renderView(
                    'reclamation/mailrender.html.twig',
                    ['reclamation' => $reclamation]
                ),
                'text/html'
                );
        $mailer->send($email);

        $this->addFlash('success', 'This reclamation added successfully');

        
     


        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
        'test'=> $test,
    ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        // $ob = new Highchart();

        // $ob->chart->renderTo('piechart');

        // $ob->title->text('stat type');

        // $ob->plotOptions->pie(array(

        //     'allowPointSelect' => true,

        //     'cursor' => 'pointer',

        //     'dataLabels' => array('enabled' => false),

        //     'showInLegend' => true

        // ));




        // $reclamation= $repository->findAll();




        // $data2=[];

        // $stattype=[];

        // $categCount=[];

        // $somme=0;

        // $compt[]=0;




        // foreach($reclamation as $reclamation){

        //     $stattype[] = $reclamation->getType();

        //     $categCount[] = $repository->getproduits();







        // }

        // $i=0;

        // foreach($stattype as $p)

        // {

        //     array_push($data2,[$stattype[$i],$categCount[0][$i]['num']]);

        //     $i++;

        // }

        // $ob->series(array(array('type' => 'pie','name' => 'typeR', 'data' => $data2)));


        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation, 
        ]);




    }



    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/accept', name: 'app_reclamation_accept', methods: ['GET', 'POST'])]
    public function accept(ReclamationRepository $reclamationRepository, $id): Response
    {
        
        $rec = $reclamationRepository-> find($id);
        $rec->setState("traitee");
        $reclamationRepository->save($rec, true);
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
            // $this->addFlash('danger', 'This reclamation deleted successfully');
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
