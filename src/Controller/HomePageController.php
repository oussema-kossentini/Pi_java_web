<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;



class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
        ]);
    }
    #[Route('/edit', name: 'app_edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, User $user, UserRepository $userRepository,Security $security): Response
    {
       $a= $user->getId();
       if ($user->getId() !== $security->getUser()->getId()) {
        
        $previousUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "http://127.0.0.1:8001/$a/edit";
        ?>
       <!DOCTYPE html>
<html>
<head>
    <title>Unauthorized Access</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 2em;
            margin-bottom: 1em;
        }
        p {
            margin-bottom: 1em;
        }
        a {
            display: inline-block;
            padding: 1em 2em;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 0.25em;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #0062cc;
        }
    </style>
</head>
<body>
    <h1>Unauthorized Access</h1>
    <p>You are not allowed to edit this user.</p>
    <p><a href="http://127.0.0.1:8001/" onclick="window.history.back();">Return to your profile</a></p>

    <script>
        window.location.replace('<?php echo $previousUrl; ?>');
    </script>
</body>
</html>

        <?php
    }
    
        
        
        
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something went wrong
                }
    
                // Update the 'image' property of the user entity
                $user->setImage($newFilename);
            }
            $user = $form->getData();
            $userRepository->save($user, true);
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('app_home_page');
        }
    
        return $this->render('security/edit.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),
            #nzid houni image bsh image tjik fel base de donnees
        ]);
    }
    
   
}
