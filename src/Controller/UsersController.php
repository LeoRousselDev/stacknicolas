<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Users;
use App\Form\UsersType;
use App\Form\UsersAdminType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//Pour la modification du mot de passe
use App\Entity\Password;
use App\Form\PasswordsType;
use Symfony\Component\Form\FormError;   //pour ajouter des erreurs à afficher
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//Pour l'historique des commandes
use App\Entity\Orders;
use App\Repository\OrdersRepository;
use App\Entity\OrderDetails;
use App\Repository\OrderDetailsRepository;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController {

    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    /* Fonction de recherche des utilisateurs */
    public function index(UsersRepository $usersRepository): Response {
        return $this->render('users/index.html.twig', [
                    'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="users_new", methods={"GET","POST"})
     */
    /* fonction d'affichage des utilisateurs */
    public function new(Request $request): Response {
        $user = new Users();
        $form = $this->createForm(UsersAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            /*$this->addFlash('add_user', 'Utilisateur ajouté');*/


            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/new.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_show", methods={"GET"})
     */
    /* Fonction d'affichage de l'utilisateur */
    public function show(Users $user): Response {
        return $this->render('users/show.html.twig', [
                    'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    /* Fonction d'édition et de verification  de l'utilisateur */
    public function edit(Request $request, Users $user): Response {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getType()){   //si le client est un particulier
                $user->setCoefficient(1);   //met le coefficient initial
            }else{  //si c'est une entreprise
                $user->setCoefficient(2);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profil');
        }

        return $this->render('users/edit.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/editAdmin", name="users_editAdmin", methods={"GET","POST"})
     */
    /* Fonction d'édition et de verification  de l'utilisateur */
    public function editAdmin(Request $request, Users $user): Response {
        $form = $this->createForm(UsersAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getType()){   //si le client est un particulier
                $user->setCoefficient(1);   //met le coefficient initial
            }else{  //si c'est une entreprise
                $user->setCoefficient(2);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_index');
        }

        return $this->render('users/edit_admin.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_delete", methods={"DELETE"})
     */
    /* Fonction de suppression, vérification d'identité et de droits pour les utilisateurs */
    public function delete(Request $request, Users $user): Response {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->get('security.token_storage')->setToken(null);   //force la déconnexion
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);  //crée la requête de suppression
            $entityManager->flush();    //lance la requête
        }
        return $this->redirectToRoute('home');
    }
    
    /**
     * @Route("/{id}/password", name="users_ChangePassword", methods={"GET","POST"})
     */
    public function ChangePassword(Request $request, Users $user,UserPasswordEncoderInterface $encoder): Response
    {
        $password = new Password(); //Crée un nouvel objet de type password
        $form = $this->createForm(PasswordsType::class, $password); //crée un formulaire pour cet objet
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data=$form->getData();
            $oldPassword = $data->getOldPassword(); //récupère la l'ancien mot de passe entrée par l'utilisateur
            $newPassword = $data->getNewPassword(); //récupère le nouveau mot de passe
            if(password_verify($oldPassword,$user->getPassword())){  //Si l'ancien mot de passe correspond
                $entityManager = $this->getDoctrine()->getManager();
                $user->setPassword(password_hash($newPassword,PASSWORD_BCRYPT));   //hash le nouveau mot de passe 
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('profil');    //redirige sur la page profil
            }else{   //sinon, l'ancien mot de passe ne correspond pas 
                $form->get('oldPassword')->addError(new FormError('Ancien mot de passe incorrect'));    //On ajoute l'erreur à afficher
            }
            
        }

        return $this->render('users/edit_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/orders", name="users_orders", methods={"GET"})
     */
    /* Fonction d'affichage de l'historique de commande de l'utilisateur */
    public function orders(Users $user, OrdersRepository $ordersRepository, OrderDetailsRepository $orderDetailsRepository){
        $orders=$ordersRepository-> findBy(array('UsersID' => $user->getId())); //récupère les commandes de l'utilisateur
        
        return $this->render('users/users_orders.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}