<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
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
    private $dateOrder;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceOrder;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateOrder(): ?string
    {
        return $this->dateOrder;
    }

    public function setDateOrder(string $dateOrder): self
    {
        $this->dateOrder = $dateOrder;

        return $this;
    }

    public function getPriceOrder(): ?int
    {
        return $this->priceOrder;
    }

    public function setPriceOrder(int $priceOrder): self
    {
        $this->priceOrder = $priceOrder;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}