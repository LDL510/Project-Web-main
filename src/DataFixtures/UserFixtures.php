<?php
 
 namespace App\DataFixtures;
  
 use App\Entity\Admin;
 use Doctrine\Bundle\FixturesBundle\Fixture;
 use Doctrine\Persistence\ObjectManager;
 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
  
 class UserFixtures extends Fixture
 {
     private $encoder;
  
     public function __construct(UserPasswordEncoderInterface $encoder)
     {
         $this->encoder = $encoder;
     }
  
     public function load(ObjectManager $manager)
     {
         $user = new Admin();
         $user->setEmail('admin@gmail.com');
         $user->setPassword($this->encoder->encodePassword($user,'longdz'));
         $user->setRoles(["ROLE_USER"]);

         $manager->persist($user);
  
         $manager->flush();
     }
 }