<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
// use Symfony\Component\Mailer\Email;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;

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



    public function __construct (PaginatorInterface $paginator)
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
    #[Route('/counts', name: 'app_reclamation_counts', methods: ['GET'])]
    public function getComplaintCounts(ReclamationRepository $reclamationRepository): JsonResponse
{
    $counts = [];
    $reclamations = $reclamationRepository->findAll();
    foreach ($reclamations as $reclamation) {
        $type = $reclamation->getType();
        if (isset($counts[$type])) {
            $counts[$type]++;
        } else {
            $counts[$type] = 1;
        }
    }
    return $this->json($counts);
}
//version 
#[Route('/statreclamation', name: 'app_reclamation_index277', methods: ['GET'])]
public function index277(ReclamationRepository $reclamationRepository): Response
{

    
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
    public function index(Request $request ,ReclamationRepository $reclamationRepository,PaginatorInterface $paginator): Response
    {
        $queryBuilder = $reclamationRepository->createQueryBuilder('r');

        $pagination =$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 // nombre d'éléments par page
        );

        return $this->render('reclamation/index.html.twig', [
            'pagination' => $pagination,
            'rec1' => $reclamationRepository->findAll(),
        ]);


        // return $this->render('reclamation/index.html.twig', [
        //     'rec1' => $reclamationRepository->findAll(),
        // ]);
    }
    #[Route('/removestatuttraitee', name: 'app_reclamation_index_remove', methods: ['GET'])]
    public function removeTraiteeAction(Request $request, PaginatorInterface $paginator,ReclamationRepository $repository): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Get all reclamations
    $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();

    // Remove reclamations with state=traitee
    foreach ($reclamations as $key => $reclamation) {
        if ($reclamation->getState() == 'traitee') {
            unset($reclamations[$key]);
        }
    }

    // Paginate the remaining reclamations
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->getInt('page', 1),
        10
    );

    // Render the updated list of reclamations
    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}



 
// #[Route('/removesall', name: 'app_reclamation_index_removeallrec', methods: ['GET'])]
// public function removeAll(EntityManagerInterface $em,Request $request, PaginatorInterface $paginator,ReclamationRepository $repo)
// {
//     $repo = $em->getRepository(Reclamation::class);
//     $reclamations = $repo->findAll();
//     if (count($reclamations) > 1) {
//         array_splice($reclamations, 1);
//         foreach ($reclamations as $reclamation) {
//             $em->remove($reclamation);
//         }
//         $em->flush();
//     }
//     $this->addFlash('success', 'All reclamations have been deleted except for the first one.');

//     // Paginate the remaining reclamations
//     $pagination = $paginator->paginate(
//         $reclamations,
//         $request->query->getInt('page', 1),
//         10
//     );
//     $entityManager = $this->getDoctrine()->getManager();

//     // Get all reclamations
//     $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
//     return $this->render('reclamation/index.html.twig', [
//         'pagination' => $pagination,
//         'rec1' => $repo->findAll(),
//     ]);
// }

#[Route('/removesall', name: 'app_reclamation_index_removeallrec', methods: ['GET'])]
public function removeAll(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator, ReclamationRepository $repo): Response
{
    $reclamations = $repo->findAll();
    
    if (count($reclamations) > 1) {
        array_splice($reclamations, 1);
        
        foreach ($reclamations as $reclamation) {
            $em->remove($reclamation);
        }
        
        $em->flush();
    }
    
    $this->addFlash('success', 'All reclamations have been deleted except for the first one.');

    // Paginate the remaining reclamations
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->getInt('page', 1),
        10
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repo->findAll(),
    ]);
}



#[Route('/removestatutnontraitee', name: 'app_reclamation_index_removenon', methods: ['GET'])]
    public function removenonTraiteeAction(Request $request, PaginatorInterface $paginator,ReclamationRepository $repository): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    // Get all reclamations
    $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();

    // Remove reclamations with state=traitee
    foreach ($reclamations as $key => $reclamation) {
        if ($reclamation->getState() == 'non traitee') {
            unset($reclamations[$key]);
        }
    }

    // Paginate the remaining reclamations
    $pagination = $paginator->paginate(
        $reclamations,
        $request->query->getInt('page', 1),
        10
    );

    // Render the updated list of reclamations
    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
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

      
    // #[Route('/date', name: 'app_rec_date', methods: ['GET'])]
    // function decroissantDate(ReclamationRepository $repository){
    //     $rec = $repository->trie_decroissant_date();
        




        
    //     return $this->render('reclamation/index.html.twig',['pagination' => $rec]);
    // }




    #[Route('/date', name: 'app_rec_date', methods: ['GET'])]
function decroissantDate(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->orderBy('r.date', 'DESC');

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}
#[Route('/datea', name: 'app_rec_dateasc', methods: ['GET'])]
function croissantDate(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->orderBy('r.date', 'ASC');

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}







#[Route('/type', name: 'app_rec_type', methods: ['GET'])]
function decroissantType(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->orderBy('r.type', 'DESC');

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}

#[Route('/typea', name: 'app_rec_typeasc', methods: ['GET'])]
function croissantType(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->orderBy('r.type', 'asc');

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}


#[Route('/typelivraison', name: 'app_rec_typelivraison', methods: ['GET'])]
function Typelivraison(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->where('r.type = :type')
        ->setParameter('type', 'livraison');
        

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}

#[Route('/typepaiement', name: 'app_rec_typepaiement', methods: ['GET'])]
function Typepaiement(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->where('r.type = :type')
        ->setParameter('type', 'paiement');
        

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}


#[Route('/typeservice', name: 'app_rec_typeservice', methods: ['GET'])]
function Typeservice(Request $request, ReclamationRepository $repository, PaginatorInterface $paginator)
{
    $queryBuilder = $repository->createQueryBuilder('r')
        ->where('r.type = :type')
        ->setParameter('type', 'service');
        

    $pagination = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10 // nombre d'éléments par page
    );

    return $this->render('reclamation/index.html.twig', [
        'pagination' => $pagination,
        'rec1' => $repository->findAll(),
    ]);
}


#[Route('/typeall', name: 'app_rec_typeall', methods: ['GET'])]
function Typeall(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator)
{
    $queryBuilder = $reclamationRepository->createQueryBuilder('r');

        $pagination =$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10 // nombre d'éléments par page
        );

        return $this->render('reclamation/index.html.twig', [
            'pagination' => $pagination,
            'rec1' => $reclamationRepository->findAll(),
        ]);


}

// #[Route('/reclient{email}', name: 'app_reclamation_reclamationclientx', methods: ['GET'])]
// function afficherreclamationclient(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator)
// {
    


//     $queryBuilder = $repository->createQueryBuilder('r')
//         ->where('r.email = :getemail');
        



//         $pagination =$paginator->paginate(
//             $queryBuilder,
//             $request->query->getInt('page', 1),
//             10 // nombre d'éléments par page
//         );

//         return $this->render('reclamation/index.html.twig', [
//             'pagination' => $pagination,
//             'rec1' => $reclamationRepository->findAll(),
//         ]);


// }











    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository, FlashyNotifier $flashy, \Swift_Mailer $mailer,\Twig\Environment $twig): Response
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

        
      
        return $this->redirectToRoute('app_reclamation_indexmerci', [
            'id' => $reclamation->getId(),
            'email' => $reclamation->getEmail()
        ], Response::HTTP_SEE_OTHER);
    

        // return $this->redirectToRoute('app_reclamation_indexmerci', ['id' => $reclamation->getId(),'email' => $reclamation->getEmail()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/new.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
        'test'=> $test,
    ]);
    }

    // #[Route('/merci{id}', name: 'app_reclamation_indexmerci', methods: ['GET'])]
    // public function showmerci(Reclamation $reclamation,String $email): Response
    // {
       
    //      $email = $reclamation->getEmail();
    //     return $this->render('reclamation/merci.html.twig', [
    //         'reclamation' => $reclamation,
    //          'email' => $email,
    //     ]);
       
    //     return $this->redirectToRoute('app_reclamation_reclamationclientemail', ['email' => $email]);

    // }
//     #[Route('/merci{id}', name: 'app_reclamation_indexmerci', methods: ['GET'])]
// public function showmerci(Reclamation $reclamation, String $email): Response
// {
//     return $this->render('reclamation/merci.html.twig', [
//         'reclamation' => $reclamation,
//         'email' => $email,
//     ]);

//     return $this->redirectToRoute('app_reclamation_reclamationclientemail', ['email' => $email]);
// }

#[Route('/merci/{id}/', name: 'app_reclamation_indexmerci', methods: ['GET'])]
public function showmerci(Reclamation $reclamation,): Response
{
    return $this->render('reclamation/merci.html.twig', [
        'reclamation' => $reclamation,
        
    ]);

 
}

#[Route('/reclamation/client/{id}', name: 'app_reclamation_reclamationclientemail', methods: ['GET'])]
public function showReclamationsByEmail(int $id, ReclamationRepository $reclamationRepository): Response
{



    
    $reclamation = $reclamationRepository->findOneBy(['id' => $id]);

    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    $email = $reclamation->getEmail();
    $reclamations = $reclamationRepository->findBy(['email' => $email]);

    return $this->render('reclamation/reclamations_by_email.html.twig', [
        'reclamations' => $reclamations,
        'email' => $email,
    ]);
}


    // $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);

    // // Check if the reclamation exists
    // if (!$reclamation) {
    //     throw $this->createNotFoundException('Reclamation not found for id '.$id);
    // }

    // // Get the response entity for the reclamation
    // $reponse = $reclamation->getReponses()->filter(function ($reponse) {
    //     return $reponse->getState() === 'traité';
    // })->first();

    // // Render the view with the reclamation and response entities
    // return $this->render('reclamation/view_reclamation_response.html.twig', [
    //     'reclamation' => $reclamation,
    //     'reponse' => $reponse,
    // ]);


#[Route('/reclamation/reponse/{id}', name: 'view_reclamation_response', methods: ['GET'])]


    public function viewReclamationResponse(Request $request, Reclamation $reclamation): Response
{
    $reponse = $reclamation->getReponse();

    return $this->render('reclamation/reponse.html.twig', [
        'reclamation' => $reclamation,
        'reponse' => $reponse
    ]);
}







    // #[Route('/reclamation/client/{id}', name: 'app_reclamation_reclamationclientemail', methods: ['GET'])]
    // public function showReclamationsByEmail(string $email, ReclamationRepository $reclamationRepository): Response
    // {
    //     $reclamations = $reclamationRepository->find['r.email where r.id =id recu par le get]);
    
    //     return $this->render('reclamation/reclamations_by_email.html.twig', [
    //         'reclamations' => $reclamations,
    //         'email' => $email,
    //     ]);
    // }
    

    // #[Route('/reclient{email}', name: 'app_reclamation_reclamationclientemail', methods: ['GET'])]
    // function afficherreclamationClientx(Request $request, ReclamationRepository $reclamationRepository, PaginatorInterface $paginator,String $email)
    // {
       

    //     $queryBuilder = $reclamationRepository->createQueryBuilder('r')
    //         ->where('r.email = :email')
    //         ->setParameter('email', $email);
    
    //     $pagination = $paginator->paginate(
    //         $queryBuilder,
    //         $request->query->getInt('page', 1),
    //         10 // nombre d'éléments par page
    //     );
    
    //     return $this->render('reclamation/index.html.twig', [
    //         'pagination' => $pagination,
    //         'rec1' => $reclamationRepository->findAll(),
    //     ]);
    // }
    


    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
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
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository, FlashyNotifier $flashy,): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
            $this->addFlash('danger', 'This reclamation deleted successfully');
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
