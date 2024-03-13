<?php

namespace App\Controller;
use App\Entity\InformationCustomers;
use App\Form\InformationCustomerType;
use App\Entity\User;
use App\Security\LoginFormAuthenticatiorAuthenticator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class InformationCustomersController extends AbstractController
{
    
    /**
     * @Route("/information/customers", name="app_information_customers")
     */
    public function index(): Response
    {
        return $this->render('information_customers/index.html.twig', [
            'controller_name' => 'InformationCustomersController',
        ]);
    }

      /**
 * @Route("/customers/details/{id}", name="customer_details")
 */
public function editAction($id, Request $request, SluggerInterface  $slugger )
{
    $em = $this->getDoctrine()->getManager();
    $customer = $em->getRepository('App\Entity\InformationCustomers')->find($id);     
    $form = $this->createForm(InformationCustomerType::class, $customer);
                
    if ($this->saveChanges( $form, $request, $customer,  $slugger)) {
        $this->addFlash(
            'notice',
            'Todo Edited'
        );
    }
    return $this->render('information_customers/index.html.twig', [
        'form' => $form->createView()
    ]);
}
public function saveChanges(  $form, $request, $customer,  $slugger)
  {
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $customer = $form->getData();
        $user = $this->get('security.token_storage')->getToken()->getUser();
          $customer->setUser($user);
          $em = $this->getDoctrine()->getManager();
          $em->persist($customer);
          $em->flush();

          return true;
      }
      return false;
  }

}