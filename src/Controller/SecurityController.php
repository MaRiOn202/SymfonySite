<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    #[Route('/register', name: 'security_register')]
    public function register(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form= $this->createForm(RegisterType::class, $user);

        //Analyse de la requête par le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //traitement des données reçues du formulaire
            $password_hash = $hasher->hashPassword($user,$user->getPassword());
            $user->setPassword($password_hash);
            //dd($user);                                   //vérifie que ça fonctionne
            $this->manager->persist($user);                //prépare le manager à sauvegarder les données dans user
            $this->manager->flush();                      //les sauvegarde
            return $this->redirectToRoute("security_login");
        };

        return $this->render('security/index.html.twig', [
            'controller_name' => 'Inscription',
            'form' => $form->createView(),
        ]);
    }


    #[Route('/login', name: 'security_login')]
    public function login(): Response{
        return $this->render('security/login.html.twig');
    }

    #[Route('/logout', name: 'security_logout')]
    public function logout(){

    }
}
