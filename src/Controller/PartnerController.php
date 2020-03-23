<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/partner")
 */
class PartnerController extends AbstractController {

    /**
     * @Route("/", name="partner_index", methods={"GET"})
     */
    /* Fonction d'affichage sur la vue des partenaires */
    public function index(PartnerRepository $partnerRepository): Response {
        return $this->render('partner/index.html.twig', [
                    'partners' => $partnerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="partner_new", methods={"GET","POST"})
     */
    /* Fonction d'ajout de nouveaux partenaires */
    public function new(Request $request): Response {
        $partner = new Partner();
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partner);
            $entityManager->flush();

            return $this->redirectToRoute('partner_index');
        }

        return $this->render('partner/new.html.twig', [
                    'partner' => $partner,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="partner_show", methods={"GET"})
     */
    /* Fonction d'affichage des partenaires */
    public function show(Partner $partner): Response {
        return $this->render('partner/show.html.twig', [
                    'partner' => $partner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="partner_edit", methods={"GET","POST"})
     */
    /* Fonction de vérification, d'édition et d'envoie de requete de partenaires  */
    public function edit(Request $request, Partner $partner): Response {
        $form = $this->createForm(PartnerType::class, $partner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* Demande a Doctrine de d'utiliser le Manager pour soumettre la requete */
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('partner_index');
        }

        return $this->render('partner/edit.html.twig', [
                    'partner' => $partner,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="partner_delete", methods={"DELETE"})
     */
    /* Requete de suppression de partenaire */
    public function delete(Request $request, Partner $partner): Response {
        /* CSRF => Usurpation d'identité, la fonction 'idCsrfTokerValid' permet de verifier si l'utilisateur à les droits de suppression */
        if ($this->isCsrfTokenValid('delete' . $partner->getId(), $request->request->get('_token'))) {
            /* Demande a Doctrine de d'utiliser le Manager pour la suppression */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('partner_index');
    }

}
