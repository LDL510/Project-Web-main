<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $nameProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityProduct;

    /**
     * @ORM\Column(type="integer")
     */
    private $priceProduct;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $informationProduct;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageProduct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduct(): ?string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;

        return $this;
    }

    public function getQuantityProduct(): ?int
    {
        return $this->quantityProduct;
    }

    public function setQuantityProduct(int $quantityProduct): self
    {
        $this->quantityProduct = $quantityProduct;

        return $this;
    }

    public function getPriceProduct(): ?int
    {
        return $this->priceProduct;
    }

    public function setPriceProduct(int $priceProduct): self
    {
        $this->priceProduct = $priceProduct;

        return $this;
    }

    public function getInformationProduct(): ?string
    {
        return $this->informationProduct;
    }

    public function setInformationProduct(?string $informationProduct): self
    {
        $this->informationProduct = $informationProduct;

        return $this;
    }

    public function getImageProduct(): ?string
    {
        return $this->imageProduct;
    }

    public function setImageProduct(string $imageProduct): self
    {
        $this->imageProduct = $imageProduct;

        return $this;
    }
}