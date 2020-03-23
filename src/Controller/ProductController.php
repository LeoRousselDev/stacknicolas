<?php

namespace App\Controller;

/* Appel des éléments qui seront utilisés par ce controller */

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CartController;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController {

    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    /* Fonction de recherche des produits */
    public function index(ProductRepository $productRepository): Response {
        return $this->render('product/index.html.twig', [
                    'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    /* Fonction de création et de vérification de nouveaux produits */
    public function new(Request $request): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setCreatedAt(new \Datetime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('manager_product');
        }

        return $this->render('product/new.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    /* Fonction d'affichage des catégories  */
    public function show(Product $product, OrderDetailsRepository $orderdetails): Response {
        return $this->render('product/show.html.twig', [
                    'product' => $product,
                    'monthlysales' => $orderdetails->findMonthlySales($product->getId()),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    /* Fonction d'édition et de vérification des catégories */
    public function edit(Request $request, Product $product): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manager_product');
        }

        return $this->render('product/edit.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    /* Fonction de suppression, vérification d'identité et de droits pour les catégories */
    public function delete(Request $request, Product $product): Response {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

}
