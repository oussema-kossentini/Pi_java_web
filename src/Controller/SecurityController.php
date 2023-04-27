<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationFormType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormError;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Security\Core\User\UserInterface;


use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;

use App\Security\SecurityAuthenticator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path:'/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('/account', name: 'app_account',methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
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
