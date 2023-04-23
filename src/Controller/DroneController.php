<?php

namespace App\Controller;

use App\Entity\Drone;
use App\Form\DroneType;
use App\Repository\DroneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/drone')]
class DroneController extends AbstractController
{
    #[Route('/', name: 'app_drone_index', methods: ['GET'])]
    public function index(Request $request,DroneRepository $droneRepository,  PaginatorInterface $paginator): Response
    {
        $drone = $droneRepository->findAll();
        $drone = $paginator->paginate
        (
            $drone, /* query NOT result */
            $request->query->getInt('page', 1),
            1
        );

       
        return $this->render('drone/index.html.twig', [
            'drone' => $drone,
        ]);
    }

    #[Route('/new', name: 'app_drone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DroneRepository $droneRepository): Response
    {
        $drone = new Drone();
        $form = $this->createForm(DroneType::class, $drone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $droneRepository->save($drone, true);

            return $this->redirectToRoute('app_drone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('drone/new.html.twig', [
            'drone' => $drone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_drone_show', methods: ['GET'])]
    public function show(Drone $drone): Response
    {
        return $this->render('drone/show.html.twig', [
            'drone' => $drone,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_drone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Drone $drone, DroneRepository $droneRepository): Response
    {
        $form = $this->createForm(DroneType::class, $drone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $droneRepository->save($drone, true);

            return $this->redirectToRoute('app_drone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('drone/edit.html.twig', [
            'drone' => $drone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_drone_delete', methods: ['POST'])]
    public function delete(Request $request, Drone $drone, DroneRepository $droneRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$drone->getId(), $request->request->get('_token'))) {
            $droneRepository->remove($drone, true);
        }

        return $this->redirectToRoute('app_drone_index', [], Response::HTTP_SEE_OTHER);
    }
}
