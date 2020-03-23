<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Contact;
use App\Form\Contact1Type;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact")
 */
/* Date init in localtime */

class ContactController extends AbstractController {

    /**
     * @Route("/contact_admin", name="contact_index", methods={"GET"})
     */
    /* Fonction index pour afficher les contacts sur la vue */
    public function index(ContactRepository $contactRepository): Response {
        return $this->render('contact/index.html.twig', [
                    'contacts' => $contactRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="contact_new", methods={"GET","POST"})
     */
    /* Fonction de création et d'envoie de demande contact */
    public function new(Request $request): Response {
        $contact = new Contact();
        $form = $this->createForm(Contact1Type::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* Add the date of the day */
            $contact->setDateSend(new \DateTime);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('contact_new');
        }

        return $this->render('contact/new.html.twig', [
                    'contact' => $contact,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_show", methods={"GET"})
     */
    /* Fonction d'affichage de la vue */
    public function show(Contact $contact): Response {
        return $this->render('contact/show.html.twig', [
                    'contact' => $contact,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="contact_edit", methods={"GET","POST"})
     */
    /* Fonction de vérification, d'édition et d'envoie de requete de demande de contact */
    public function edit(Request $request, Contact $contact): Response {
        $form = $this->createForm(Contact1Type::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/edit.html.twig', [
                    'contact' => $contact,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="contact_delete", methods={"DELETE"})
     */
    /* Fonction de verification d'identité et de suppression de fiche de contact */
    public function delete(Request $request, Contact $contact): Response {
        /* CSRF => Usurpation d'identité, la fonction 'idCsrfTokerValid' permet de verifier si l'utilisateur à les droits de suppression */
        if ($this->isCsrfTokenValid('delete' . $contact->getId(), $request->request->get('_token'))) {
            /* Demande a Doctrine de d'utiliser le Manager pour la suppression */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contact);
            $entityManager->flush();
        }

        return $this->redirectToRoute('contact_index');
    }

}