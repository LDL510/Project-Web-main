<?php

namespace App\Entity;

use App\Repository\InformationCustomersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InformationCustomersRepository::class)
 */
class InformationCustomers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nameCustomer;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phoneCustomer;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $addressCustomer;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getNameCustomer(): ?string
    {
        return $this->nameCustomer;
    }

    public function setNameCustomer(string $nameCustomer): self
    {
        $this->nameCustomer = $nameCustomer;

        return $this;
    }

    public function getPhoneCustomer(): ?string
    {
        return $this->phoneCustomer;
    }

    public function setPhoneCustomer(?string $phoneCustomer): self
    {
        $this->phoneCustomer = $phoneCustomer;

        return $this;
    }

    public function getAddressCustomer(): ?string
    {
        return $this->addressCustomer;
    }

    public function setAddressCustomer(string $addressCustomer): self
    {
        $this->addressCustomer = $addressCustomer;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}