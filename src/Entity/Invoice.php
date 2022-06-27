<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $number;

    #[ORM\Column(type: 'integer')]
    private $companyId;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $summery;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $payedByUserId;

    #[ORM\Column(type: 'integer')]
    private $vatNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private $terms;

    #[ORM\Column(type: 'float')]
    private $subtotal;

    #[ORM\Column(type: 'float')]
    private $vat;

    #[ORM\Column(type: 'float')]
    private $total;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[ORM\Column(type: 'integer')]
    private $payed;

    #[ORM\Column(type: 'string', length: 255)]
    private $currency;


    #[ORM\Column(type: 'datetime', nullable: true)]
    private $payedDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummery(): ?string
    {
        return $this->summery;
    }

    public function setSummery(string $summery): self
    {
        $this->summery = $summery;

        return $this;
    }


    public function getVatNumber(): ?int
    {
        return $this->vatNumber;
    }

    public function setVatNumber(int $vatNumber): self
    {
        $this->vatNumber = $vatNumber;

        return $this;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): self
    {
        $this->terms = $terms;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(float $subtotal): self
    {
        $this->subtotal = $subtotal;

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

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPayed(): ?int
    {
        return $this->payed;
    }

    public function setPayed(int $payed): self
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayedByUserId()
    {
        return $this->payedByUserId;
    }

    /**
     * @param mixed $payedByUserId
     */
    public function setPayedByUserId($payedByUserId): void
    {
        $this->payedByUserId = $payedByUserId;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getPayedDate()
    {
        return $this->payedDate;
    }

    /**
     * @param mixed $payedDate
     */
    public function setPayedDate($payedDate): void
    {
        $this->payedDate = $payedDate;
    }
}
