<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits', name: 'products_')]
class ProductsController extends AbstractController {
    #[Route('/', name: 'index')]
    public function index() : Response {
        return $this->render('products/index.html.twig');
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Products $product) : Response {
    
        return $this->render('products/details.html.twig', compact('product'));
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