<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Security\Voter\ProductsVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController {
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository) : Response {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', compact('produits'));
    }

    #[Route('/ajouter', name: 'add')]
    public function add() : Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Products $product) : Response {
        //vérifier si l'user peut éditer grâce au Voter (l'attribut + l'objet produit)
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Products $product) : Response {
        //vérifier si l'user peut supprimer grâce au Voter (l'attribut + l'objet produit)
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}

