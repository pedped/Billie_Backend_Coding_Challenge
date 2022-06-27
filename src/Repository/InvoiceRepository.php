<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\InvoiceLineItem;
use App\Enums\InvoicePayedStatus;
use App\Helpers\StringHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    private CompanyRepository $companyRepository;

    public function __construct(ManagerRegistry $registry, CompanyRepository $companyRepository)
    {
        parent::__construct($registry, Invoice::class);
        $this->companyRepository = $companyRepository;
    }

    public function add(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     *  create a new invoice
     * @param int $ownerUserId
     * @param string $companyId
     * @param string $title
     * @param string $summery
     * @param string $vatNumber
     * @param string $terms
     * @param string $currency
     * @param string $lineItems
     * @return Invoice
     * @throws \Exception
     */
    public function createInvoice(int $ownerUserId, string $companyId, string $title, string $summery, string $vatNumber, string $terms, string $currency, string $lineItems): Invoice
    {
        // first, check if the company exists and the user belong to this company
        $company = $this->companyRepository->findOneBy([
            "id" => $companyId,
            "userId" => $ownerUserId
        ]);
        if (!$company) {
            throw new \Exception('Invalid Company, this company do not belongs to you');
        }


        // now, check if the company did not reach the unpayed invoice
        $unPayedInvoice = $this->count([
            "companyId" => $company->getId(),
            "payed" => InvoicePayedStatus::$NOT_PAYED
        ]);
        if ($unPayedInvoice > $company->getDebtorLimit()) {
            throw new \Exception('This company reached its debtor limit, wait for users to pay invoices');
        }

        // now, load the line items
        $lineItemsEncoded = $lineItems;

        // try to decode line items
        $subTotal = 0;
        $vat = 0;
        $total = 0;
        $lineItems = json_decode($lineItemsEncoded);
        foreach ($lineItems as $lineItem) {
            $subTotal += $lineItem->unit_price;
            $vat += $lineItem->vat * $lineItem->unit_price;
            $total += $lineItem->vat * $lineItem->unit_price + $lineItem->unit_price;
        }

        // create admin user
        $invoice = new Invoice();
        $invoice->setCompanyId($companyId);
        $invoice->setNumber("invoice - " . StringHelper::generateRandomString(6));
        $invoice->setTitle($title);
        $invoice->setSummery($summery);
        $invoice->setTerms($terms);
        $invoice->setCurrency($currency);
        $invoice->setVatNumber($vatNumber);
        $invoice->setSubtotal($subTotal);
        $invoice->setVat($vat);
        $invoice->setTotal($total);
        $invoice->setPayed(InvoicePayedStatus::$NOT_PAYED);
        $invoice->setDate(new \DateTime('@' . time()));

        // save data
        $this->getEntityManager()->persist($invoice);
        $this->getEntityManager()->flush();


        // now, it is time to add invoice  line items
        foreach ($lineItems as $lineItem) {
            $invoiceLineItem = new InvoiceLineItem();
            $invoiceLineItem->setVat($lineItem->vat);
            $invoiceLineItem->setAmount($lineItem->unit_price * $lineItem->quantity);
            $invoiceLineItem->setDescription($lineItem->description);
            $invoiceLineItem->setUnitPrice($lineItem->unit_price);
            $invoiceLineItem->setQuantity($lineItem->quantity);
            $invoiceLineItem->setInvocieId($invoice->getId());
            $this->getEntityManager()->persist($invoiceLineItem);
            $this->getEntityManager()->flush();
        }

        return $invoice;
    }

    /**
     * @param int $invoiceId
     * @return void
     * @throws \Exception
     */
    public function setPayed(int $invoiceId)
    {
        // find invoice by id
        $invoice = $this->findOneBy([
            "id" => $invoiceId
        ]);

        if (!$invoice) {
            throw new \Exception('Invoice not found');
        }

        if ($invoice->getPayed() == InvoicePayedStatus::$PAYED) {
            throw new \Exception('Invoice payed before');
        }

        // invoice is not payed before, make it as payed
        $invoice->setPayed(InvoicePayedStatus::$PAYED);
        $invoice->setPayedByUserId($this->loggedInUser->getId());
        $invoice->setPayedDate(new \DateTime('@' . time()));
        $this->getEntityManager()->persist($invoice);
        $this->getEntityManager()->flush();
    }
}
