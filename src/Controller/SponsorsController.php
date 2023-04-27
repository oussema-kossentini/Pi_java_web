<?php

namespace App\Controller;

use App\Entity\Sponsors;
use App\Form\SponsorsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SponsorsRepository;

#[Route('/sponsors')]
class SponsorsController extends AbstractController
{
    #[Route('/', name: 'app_sponsors_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sponsors = $entityManager
            ->getRepository(Sponsors::class)
            ->findAll();

        return $this->render('sponsors/index.html.twig', [
            'sponsors' => $sponsors,
        ]);
    }
    #[Route('/recherchesponsor', name: 'app_sponsor_recherche')]
    public function rechercheOffre(SponsorsRepository $sponsorsRepository, Request  $request): Response
    {
        $data=$request->get('search');
        $sponsor = $sponsorsRepository->searchQB($data);
        return $this->render('sponsors/index.html.twig',
            ["sponsors" => $sponsor]);
    }

    #[Route('/new', name: 'app_sponsors_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sponsor = new Sponsors();
        $form = $this->createForm(SponsorsType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sponsor);
            $entityManager->flush();

            return $this->redirectToRoute('app_sponsors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsors/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{idSponsor}', name: 'app_sponsors_show', methods: ['GET'])]
    public function show(Sponsors $sponsor): Response
    {
        return $this->render('sponsors/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{idSponsor}/edit', name: 'app_sponsors_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsors $sponsor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SponsorsType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sponsors_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsors/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{idSponsor}', name: 'app_sponsors_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsors $sponsor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sponsor->getIdSponsor(), $request->request->get('_token'))) {
            $entityManager->remove($sponsor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sponsors_index', [], Response::HTTP_SEE_OTHER);
    }
}
