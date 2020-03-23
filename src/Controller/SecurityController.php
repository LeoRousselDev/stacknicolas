<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Users;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    /**
     * @Route("/signup", name="security_registration")
     */
    /* Fonction de création de nouvel utilisateur avec vérification */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* Impute l'encodage qui sera utilisé pour crypter le mot de passe utilisateur dans la bdd */
            $hash = $encoder->encodePassword($user, $user->getPassword());
            /* Cryptage du mot de passe que l'utilisateur aura entré */
            $user->setPassword($hash);
            if($user->getType()){   //si le client est un particulier
                $user->setCoefficient(1);   //met le coefficient initial
            }else{  //si c'est une entreprise
                $user->setCoefficient(2);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('add_user', 'Utilisateur ajouté');


            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    /* Fonction de connexion utilisateur */
     public function login(AuthenticationUtils $authenticationUtils): Response
        {
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            return $this->render('security/login.html.twig', [
                'last_username'=>$lastUsername,
                'error'=>$error
            ]);
        }

    /**
     * @Route("/logout", name="security_logout")
     */
    /* Fonction de déconnexion utilisateur */
    public function logout() {
        
    }

}