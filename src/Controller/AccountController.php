<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
class AccountController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/account', name: 'app_account',methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $form = $this->createFormBuilder()
        ->add('searchedby', TextType::class)
        ->add('search', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $searchTerm = strtolower($data['searchedby']);

        $queryBuilder = $userRepository->createQueryBuilder('d')
            ->where('LOWER(d.nom) LIKE :searchTerm')
            ->orWhere('LOWER(d.prenom) LIKE :searchTerm')
            ->orWhere('LOWER(d.adresse_ma) LIKE :searchTerm')
            ->orWhere('LOWER(d.num_tel) LIKE :searchTerm')
            ->orWhere('LOWER(d.date_naissance) LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');

        $query = $queryBuilder->getQuery();
        $users = $query->getResult();
    }else {
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
    }
   
    return $this->render('account/index.html.twig', [
        'users' => $users,

        'form'=> $form,
    ]);

    }
    #[Route('/{id}', name: 'app_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('account/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/{id}', name: 'app_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
    }
  
}
