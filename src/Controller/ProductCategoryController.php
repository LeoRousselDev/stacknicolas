<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/category")
 */
class ProductCategoryController extends AbstractController {

    /**
     * @Route("/", name="product_category_index", methods={"GET"})
     */
    /* Fonction de recherche des catégories de produit */
    public function index(ProductCategoryRepository $productCategoryRepository): Response {
        return $this->render('product_category/index.html.twig', [
                    'product_categories' => $productCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_category_new", methods={"GET","POST"})
     */
    /* Fonction de création et de vérification de nouvelles catégories */
    public function new(Request $request): Response {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productCategory);
            $entityManager->flush();

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render('product_category/new.html.twig', [
                    'product_category' => $productCategory,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_show", methods={"GET"})
     */
    /* Fonction d'affichage des catégories  */
    public function show(ProductCategory $productCategory): Response {
        return $this->render('product_category/show.html.twig', [
                    'product_category' => $productCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_category_edit", methods={"GET","POST"})
     */
    /* Fonction d'édition et de vérification des catégories */
    public function edit(Request $request, ProductCategory $productCategory): Response {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render('product_category/edit.html.twig', [
                    'product_category' => $productCategory,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_delete", methods={"DELETE"})
     */
    /* Fonction de suppression, vérification d'identité et de droits pour les catégories */
    public function delete(Request $request, ProductCategory $productCategory): Response {
        if ($this->isCsrfTokenValid('delete' . $productCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_category_index');
    }

}