<?php

namespace App\Controller;
use App\Entity\Orders;
use App\Entity\OrderDetails;
use App\Entity\Products;

use App\Form\OrdersType;
use App\Entity\User;
use App\Security\LoginFormAuthenticatiorAuthenticator;
use App\Controller\OrderDetailsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


class OrdersController extends AbstractController
{
     // /**
    //  * @Route("/order", name="app_order")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('orders/index.html.twig', [
    //         'controller_name' => 'OrdersController',
    //     ]);
    // }
       /**
     * @Route("/admin/orders",name="orders_admin")
     */
    public function ordersAdmin()
    {
        
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository(Orders::class)->findAll();
          
        return $this->render('orders/indexAdmin.html.twig', array(
            'orders' => $orders,
        ));
    }

           /**
     * @Route("/orders/{id}",name="orders_user")
     */
    public function ordersUser($id)
    {

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Orders::class)->findBy(['user' => $id]);
        return $this->render('orders/index.html.twig', array(
            'orders' => $orders,
        ));
    }

    /**
 * @Route("/order/add/{id}", name="order_add")
 */
public function addOrder($id, Request $request )
{  
      $user = $this->get('security.token_storage')->getToken()->getUser();
      $em = $this->getDoctrine()->getManager();
      $product = $em->getRepository('App\Entity\Products')->find($id);
      if  ($product -> getQuantityProduct() >= 0){   
          $order = new Orders();
          $order->setUser($user);
          $order->setDateOrder("13/3/2024");
          $order->setPriceOrder(0);
      
          $em->persist($order);
          $em->flush();
            $response = $this->forward('App\Controller\OrderDetailsController::addOrderDetails', [
              'idProduct'  =>  $id,
              'request' => $request,
              'order' => $order,
      ]);
          
          $orderDetails = $em->getRepository('App\Entity\OrderDetails')->findBy(['order_' => $order]);
          $totalPrice = 0;
            foreach ($orderDetails as $item){
                $totalPrice = $item -> getPriceOrderDetail() + $totalPrice;    
            }
          $order->setPriceOrder($totalPrice);
          $em->persist($order);
          $em->flush();
        }

       return $this->redirectToRoute('products_user');

}
    
       
public function editOrder($idOrder, Request $request )
{   
    $em = $this->getDoctrine()->getManager();
    $order = $em->getRepository('App\Entity\Orders')->find($idOrder); 
    $orderDetails = $em->getRepository('App\Entity\OrderDetails')->findBy(['order_' => $idOrder]);
    $totalPrice = 0;
      foreach ($orderDetails as $item){
            $totalPrice = $item -> getPriceOrderDetail() + $totalPrice;    
      }
          $order->setPriceOrder($totalPrice);
          $em->persist($order);
          $em->flush();
          return true;
  }
      /**
 * @Route("/order/delete/{idOrder}", name="order_delete")
 */
public function deleteOrder($idOrder, Request $request )
  {  
      $em = $this->getDoctrine()->getManager();
      $order = $em->getRepository('App\Entity\Orders')->find($idOrder);
      $response = $this->forward('App\Controller\OrderDetailsController::deleteAllAction', [
              'idOrder'  =>  $idOrder,
      ]);
      $em->remove($order);
      $em->flush();
      return $this->redirectToRoute('products_user');

  }
}    