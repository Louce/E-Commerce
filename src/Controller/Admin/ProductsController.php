<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController {
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository) : Response {
        $produits = $productsRepository->findAll();
        return $this->render('admin/products/index.html.twig', compact('produits'));
    }

    #[Route('/ajouter', name: 'add')]
    // request = requête http pour envoyer les données
    //EntityManagerInterface = stockage des données en bdd
    //SluggerInterface = gère le slug
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger) : Response {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");

        //créer un nouveau produit
        $product = new Products();

        //créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);
        // return $this->render('admin/products/add.html.twig', [
        //     'productForm' => $productForm->createView()
        // ]);

        //traiter la requête du formulaire
        $productForm->handleRequest($request);
        //vérifier si le form est soumis ET valide
        if($productForm->isSubmitted() && $productForm->isValid()) {
            //générer le slug (qui est à null si je fais un dd($productForm))
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            //passer le prix en centimes pour n'avoir que des entiers
            // $price = $product->getPrice()*100;
            // $product->setPrice($price);
            //stocker
            $em->persist($product);
            $em->flush();

            //message flash
            $this->addFlash('success', 'Produit ajouté avec succès');

            //redirection
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->renderForm('admin/products/add.html.twig', compact('productForm'));
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Products $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger) : Response {
        //vérifier si l'user peut éditer grâce au Voter (l'attribut + l'objet produit)
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
   

        //on divise le prix par 100 pour remettre le bon prix dans l'objet
        // $price = $product->getPrice()/100;
        // $product->setPrice($price);

        //créer le formulaire
        $productForm = $this->createForm(ProductsFormType::class, $product);
        // return $this->render('admin/products/add.html.twig', [
        //     'productForm' => $productForm->createView()
        // ]);

        //traiter la requête du formulaire
        $productForm->handleRequest($request);
        //vérifier si le form est soumis ET valide
        if($productForm->isSubmitted() && $productForm->isValid()) {
            //générer le slug (qui est à null si je fais un dd($productForm))
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            //passer le prix en centimes pour n'avoir que des entiers
            // $price = $product->getPrice()*100;
            // $product->setPrice($price);
            //stocker
            $em->persist($product);
            $em->flush();

            //message flash
            $this->addFlash('success', 'Produit modifié avec succès');

            //redirection
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->renderForm('admin/products/edit.html.twig', compact('productForm'));
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Products $product) : Response {
        //vérifier si l'user peut supprimer grâce au Voter (l'attribut + l'objet produit)
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        return $this->render('admin/products/index.html.twig');
    }
}

