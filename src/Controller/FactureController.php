<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Twig\Environment;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Entity\PdfGeneratorService;
use Ob\HighchartsBundle\Highcharts\Highchart;
use App\Repository\FactureRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;




#[Route('/facture')]
class FactureController extends AbstractController
{
    
    
    
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,FactureRepository $repository): Response
    {
        $factures = $entityManager
            ->getRepository(Facture::class)
            ->findAll();

       
            $factures = $repository->findAll();
        $ob = new Highchart();

        $ob->chart->renderTo('piechart');

        $ob->title->text('stat facture');

        $ob->plotOptions->pie(array(

            'allowPointSelect' => true,

            'cursor' => 'pointer',

            'dataLabels' => array('enabled' => true),

            'showInLegend' => true
            

        ));




       
        $factures= $repository->findAll();



        $data2=[];

        $categVille=[];

        $categCount=[];

        $somme=0;

        $compt[]=0;




        foreach($factures as $facture){

            $categVille[] = $facture->getVille();
            $categCount[] = $repository->getproduits();

            

        }

        $i=0;

        foreach($categVille as $p)

        {

            if (isset($categCount[0][$i]['num'])) {
                array_push($data2, [$categVille[$i], $categCount[0][$i]['num']]);
            } else {
                // handle the case where the array key does not exist
            }
            $i++;

        }

        $ob->series(array(array('type' => 'pie','name' => 'Nombre des factures', 'data' => $data2)));




        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
             'chart' => $ob,

        ]);
    
    }


    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $flashy->success('Votre facture a bien été enregistrée!', 'http://your-awesome-link.com');
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/front.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
#[Route('/facture/sort/prix', name: 'app_facture_sort')]
    public function trierParPrix(Request $request,FactureRepository $repository): JsonResponse
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Facture::class)->createQueryBuilder('p');
        $queryBuilder->orderBy('p.prix', 'ASC');

        $Facture = $queryBuilder->getQuery()->getResult();

        $factureHtml = $this->renderView('facture/tri.html.twig', ['trie' => $Facture]);
        $factures = $repository->findAll();
        $ob = new Highchart();

        $ob->chart->renderTo('piechart');

        $ob->title->text('stat facture');

        $ob->plotOptions->pie(array(

            'allowPointSelect' => true,

            'cursor' => 'pointer',

            'dataLabels' => array('enabled' => true),

            'showInLegend' => true
            

        ));




       
        $factures= $repository->findAll();



        $data2=[];

        $categVille=[];

        $categCount=[];

        $somme=0;

        $compt[]=0;




        foreach($factures as $facture){

            $categVille[] = $facture->getVille();
            $categCount[] = $repository->getproduits();

            

        }

        $i=0;

        foreach($categVille as $p)

        {

            if (isset($categCount[0][$i]['num'])) {
                array_push($data2, [$categVille[$i], $categCount[0][$i]['num']]);
            } else {
                // handle the case where the array key does not exist
            }
            $i++;

        }

        $ob->series(array(array('type' => 'pie','name' => 'Nombre des factures', 'data' => $data2)));

        return new JsonResponse(['html' => $factureHtml]);
        
    }
    public function calculerTotal(Request $request, EntityManagerInterface $entityManager,FactureRepository $repository): Response
{
    $factures = $entityManager->getRepository(Facture::class)->findAll();
    
    $total = 0;
    foreach ($factures as $facture) {
        $total += $facture->getPrix();
    }
    $factures = $repository->findAll();
        $ob = new Highchart();

        $ob->chart->renderTo('piechart');

        $ob->title->text('stat facture');

        $ob->plotOptions->pie(array(

            'allowPointSelect' => true,

            'cursor' => 'pointer',

            'dataLabels' => array('enabled' => true),

            'showInLegend' => true
            

        ));




       
        $factures= $repository->findAll();



        $data2=[];

        $categVille=[];

        $categCount=[];

        $somme=0;

        $compt[]=0;




        foreach($factures as $facture){

            $categVille[] = $facture->getVille();
            $categCount[] = $repository->getproduits();

            

        }

        $i=0;

        foreach($categVille as $p)

        {

            if (isset($categCount[0][$i]['num'])) {
                array_push($data2, [$categVille[$i], $categCount[0][$i]['num']]);
            } else {
                // handle the case where the array key does not exist
            }
            $i++;

        }

        $ob->series(array(array('type' => 'pie','name' => 'Nombre des factures', 'data' => $data2)));
    
    return $this->render('facture/index.html.twig', [
        'factures' => $factures,
        'total' => $total, // add the total variable here
        'chart' => $ob,
    ]);
}  
}