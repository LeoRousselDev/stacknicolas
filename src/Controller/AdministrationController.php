<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\UsersRepository;


class AdministrationController extends AbstractController
{
    /**
     * @Route("/administration", name="administration")
     */
    public function index(ProductRepository $productRepository, UsersRepository $usersRepository)
    {
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
            'product' => $productRepository->findAll(),
            'users' => $usersRepository->findAll(),
        ]);
    }
    
    
    /**
     * @Route("/manager_product", name="manager_product")
     */
    public function manager_product(ProductRepository $productRepository)
    {
        return $this->render('administration/manager_product.html.twig', [
                'products' => $productRepository->findAll(),
    ]);
    }
}
