<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Newletters;
use App\Form\NewlettersType;
use App\Repository\NewlettersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newletters")
 */
class NewlettersController extends AbstractController {

    /**
     * @Route("/", name="newletters_index", methods={"GET"})
     */
    /* Fonction d'affichage des newsletters */
    public function index(NewlettersRepository $newlettersRepository): Response {
        return $this->render('newletters/index.html.twig', [
                    'newletters' => $newlettersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="newletters_new", methods={"GET","POST"})
     */
    /* Fonction de création de nouvelle newsletter */
    public function new(Request $request): Response {
        $newletter = new Newletters();
        $form = $this->createForm(NewlettersType::class, $newletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newletter->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newletter);
            $entityManager->flush();

            return $this->redirectToRoute('newletters_index');
        }

        return $this->render('newletters/new.html.twig', [
                    'newletter' => $newletter,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="newletters_show", methods={"GET"})
     */
    /* Fonction d'affichage de la newsletter */
    public function show(Newletters $newletter): Response {
        return $this->render('newletters/show.html.twig', [
                    'newletter' => $newletter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="newletters_edit", methods={"GET","POST"})
     */
    /* Fonction de vérification, d'édition et d'envoie de requete de la newsletter */
    public function edit(Request $request, Newletters $newletter): Response {
        $form = $this->createForm(NewlettersType::class, $newletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('newletters_index');
        }

        return $this->render('newletters/edit.html.twig', [
                    'newletter' => $newletter,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="newletters_delete", methods={"DELETE"})
     */
    /* Fonction de suppression de newsletter (non validée) */
    public function delete(Request $request, Newletters $newletter): Response {
        /* CSRF => Usurpation d'identité, la fonction 'idCsrfTokerValid' permet de verifier si l'utilisateur à les droits de suppression */
        if ($this->isCsrfTokenValid('delete' . $newletter->getId(), $request->request->get('_token'))) {
            /* Demande a Doctrine de d'utiliser le Manager pour la suppression */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($newletter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('newletters_index');
    }

}
