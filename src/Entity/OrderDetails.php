<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_;

    /**
     * @ORM\ManyToOne(targetEntity=Products::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityOrderDetail;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceOrderDetail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Orders
    {
        return $this->order_;
    }

    public function setOrder(?Orders $order_): self
    {
        $this->order_ = $order_;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantityOrderDetail(): ?int
    {
        return $this->quantityOrderDetail;
    }

    public function setQuantityOrderDetail(int $quantityOrderDetail): self
    {
        $this->quantityOrderDetail = $quantityOrderDetail;

        return $this;
    }

    public function getPriceOrderDetail(): ?int
    {
        return $this->priceOrderDetail;
    }

    public function setPriceOrderDetail(int $priceOrderDetail): self
    {
        $this->priceOrderDetail = $priceOrderDetail;

        return $this;
    }
}