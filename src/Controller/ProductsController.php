<?php

namespace App\Controller;
use App\Entity\Products;
use App\Form\ProductsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsController extends AbstractController
{
    // /**
    //  * @Route("/products", name="app_products")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('products/index.html.twig', [
    //         'controller_name' => 'ProductsController',
    //     ]);
    // }
      /**
     * @Route("/admin/products",name="products_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository(Products::class)->findAll();

        return $this->render('products/index.html.twig', array(
            'products' => $products,
        ));
    }
    /**
     * @Route("/home",name="products_user")
     */
    public function indexActionUser()
    {
        
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository(Products::class)->findAll();

        return $this->render('user_ui/home.html.twig', array(
            'products' => $products,
        ));
    }
    /**
    * Finds and displays a car entity.
    *
    * @Route("/admin/product/{id}", name="product_show")
     */
     public function showAction(Products $product)
      {
         return $this->render('product/show.html.twig', array(
        'product' => $product,
      ));
      }
  
    /**
   * Creates a new part entity.
   *
   * @Route("/admin/products/create",methods={"GET","POST"}, name="products_create")
   */
  public function createAction(Request $request, SluggerInterface  $slugger )
  {
    $product = new Products();
    $form = $this->createForm(ProductsType::class, $product);

    if ($this->saveChanges($form, $request, $product, $slugger)) {
              $this-> addFlash(
          'notice',
          'Customer Added'
      );
      return $this->redirectToRoute('products_index');
    }

    return $this->render('products/create.html.twig', [
      'form' => $form->createView()
    ]);
  }
  public function saveChanges($form, $request, $product,  $slugger)
  {
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) {
        // $product = $form->getData();
        $imageProduct = $form->get('imageProduct')->getData();
          $product->setNameProduct($request->request->get('products')['nameProduct']);
          $product->setQuantityProduct($request->request->get('products')['quantityProduct']);
          $product->setPriceProduct($request->request->get('products')['priceProduct']);
          $product->setInformationProduct($request->request->get('products')['informationProduct']);
          if ($imageProduct) {
            $originalFilename = pathinfo($imageProduct->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageProduct->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageProduct->move(
                    $this->getParameter('app.path.product_images'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $product->setImageProduct($newFilename);
        }
          $em = $this->getDoctrine()->getManager();
          $em->persist($product);
          $em->flush();
          
          return true;
      }
      return false;
  }
    
  /**
 * @Route("/admin/products/{id}/edit", name="product_edit")
 */
public function editAction($id, Request $request, SluggerInterface  $slugger )
{
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('App\Entity\Products')->find($id);
    
    $form = $this->createForm(ProductsType::class, $product);
    
    if ($this->saveChanges($form, $request, $product,  $slugger)) {
        $this->addFlash(
            'notice',
            'Todo Edited'
        );
        return $this->redirectToRoute('products_index');
    }
    
    return $this->render('products/edit.html.twig', [
        'form' => $form->createView()
    ]);
}


/**
 * @Route("/products/delete/{id}", name="product_delete")
 */
public function deleteAction($id)
{ 

    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository('App\Entity\Products')->find($id);
    $em->remove($product);
    $em->flush();
    
    $this->addFlash(
        'error',
        'Todo deleted'
    );
  
    return $this->redirectToRoute('products_index');
  }

}
