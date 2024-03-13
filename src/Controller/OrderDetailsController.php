<?php

namespace App\Controller;
use App\Entity\OrderDetails;
use App\Entity\Products;
use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OrderDetailsType;
use App\Controller\OrdersController;


class OrderDetailsController extends AbstractController
{
    // /**
    //  * @Route("/order/details", name="app_order_details")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('order_details/index.html.twig', [
    //         'controller_name' => 'OrderDetailsController',
    //     ]);
    // }
  /**
    *
    * @Route("/order/details/{id}", name="orderDetails_user")
     */
    public function showAction( $id , Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $orderDetails = $em->getRepository('App\Entity\OrderDetails')->findBy(['order_' => $id]);
        // $forms = $this->createForm(OrderDetailCollectionType::class, $orderDetails);

        // return $this->render('order_details/index.html.twig', [
        //           'forms' => $forms->createView()
        //       ]);
        return $this->render('order_details/index.html.twig', array(
          'orderDetails' => $orderDetails,
      ));
    }
 
public function addOrderDetails($idProduct, Request $request, Orders $order)
{  
    $orderDetail = new OrderDetails();
    $em = $this->getDoctrine()->getManager();
    $product = $em->getRepository(Products::class)->find($idProduct);
    $orderDetail->setOrder($order);
    $orderDetail->setProduct($product);
    $orderDetail->setQuantityOrderDetail(1);
    $priceProduct = $product -> getPriceProduct();
    $orderDetail->setPriceOrderDetail($orderDetail -> getQuantityOrderDetail() *  $priceProduct);
    $product->setQuantityProduct( $product -> getQuantityProduct() - $orderDetail->getQuantityOrderDetail());  
      if($product ->getQuantityProduct() >= 0){
          $em->persist( $product);
          $em->persist($orderDetail);
          $em->flush();
        return true;
      }
      else{
          return false;
      }
  }
 
  /**
 * @Route("/order/details/edit/{id}", name="order_Details_edit")
 */
public function editAction($id, Request $request )
{
    $em = $this->getDoctrine()->getManager();
    $orderDetail = $em->getRepository('App\Entity\OrderDetails')->find($id);
    $form = $this->createForm(OrderDetailsType::class, $orderDetail);
    
    if ($this->saveChanges($form, $request, $orderDetail)) {
        $this->addFlash(
            'notice',
            'Todo Edited'
        );
        $id = $orderDetail -> getOrder() -> getId();
        $response = $this->forward('App\Controller\OrdersController::editOrder', [
          'idOrder'  =>  $id,
          'request' =>   $request,
            ]); 
        return $this->redirectToRoute('orderDetails_user' , array(
          'id' => $orderDetail->getOrder()->getId()));
    }
    return $this->render('order_details/edit.html.twig', [
        'form' => $form->createView()
    ],);
}
public function saveChanges(  $form, $request, $orderDetail)
  {    $em = $this->getDoctrine()->getManager();

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $product = $em->getRepository(Products::class)->find($orderDetail -> getProduct() -> getId());
        $product->setQuantityProduct( $product -> getQuantityProduct() - $orderDetail->getQuantityOrderDetail());
        
         if($product ->getQuantityProduct() >= 0){
        $em->persist( $product);
        
        $orderDetail = $form->getData();
        $orderDetail->setPriceOrderDetail($orderDetail -> getQuantityOrderDetail() *  $orderDetail -> getProduct() ->getPriceProduct() );
        $em = $this->getDoctrine()->getManager();
        $em->persist($orderDetail);
        $em->flush();
         
      return true;
         }
         else{
          $message = "This product is out of stock.";
          echo "<script type='text/javascript'>alert('$message');</script>";
         }
      }
      return false;
  }

/**
 * @Route("/order/details/delete/{id}", name="order_Details_delete")
 */
public function deleteAction($id)
{ 

    $em = $this->getDoctrine()->getManager();
    $orderDetail = $em->getRepository('App\Entity\OrderDetails')->find($id);
    $em->remove($orderDetail);
    $em->flush();
    
    return $this->redirectToRoute('orderDetails_user' , array(
      'id' => $orderDetail->getOrder()->getId()));
  }
 
public function deleteAllAction($idOrder)
{ 
    $em = $this->getDoctrine()->getManager();
    $orderDetails = $em->getRepository('App\Entity\OrderDetails')->findBy(['order_' => $idOrder]);
    foreach ($orderDetails as $item){    
      $em->remove($item);
      $em->flush();
    }
    return true;
  }
}