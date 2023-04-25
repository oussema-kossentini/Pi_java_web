<?php
namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @throws \JsonException
     */
#[Route(path:"/api/login",name: "api_login",methods:["POST","GET"])]
    Public function ApiLogin(){
        $user=new user();

$userData=[
    
    'adresse_ma'=>$user->getAdresseMa(),
    'nom'=>$user->getNom(),
    'prenom'=>$user->getPrenom(),
    'num_tel'=>$user->getNumTel(),
    'date_naissance'=>$user->getDateNaissance(),
];
return $this->json($userData);
    }
}