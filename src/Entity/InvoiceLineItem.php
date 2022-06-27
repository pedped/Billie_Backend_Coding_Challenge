<?php

namespace App\Entity;

use App\Repository\InvoiceLineItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceLineItemRepository::class)]
class InvoiceLineItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $invocieId;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'float')]
    private $quantity;

    #[ORM\Column(type: 'float')]
    private $unitPrice;

    #[ORM\Column(type: 'float')]
    private $vat;

    #[ORM\Column(type: 'float')]
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvocieId(): ?int
    {
        return $this->invocieId;
    }

    public function setInvocieId(int $invocieId): self
    {
        $this->invocieId = $invocieId;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
