<?php

namespace App\Tests\BehaviorTesting;

use App\Entity\Invoice;
use App\Exceptions\DebtorLimitException;
use App\Tests\Helper\EntityGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceManagementTest extends WebTestCase
{
    public function test_create_invoice(): void
    {
        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // get faker
        $faker = \Faker\Factory::create();

        // get entities
        $entityGenerator = new EntityGenerator($entityManager);
        $entityGenerator->generateUser()->generateUserToken()->generateCompany(5);

        // get repository
        $invoiceRepository = $entityManager->getRepository(Invoice::class);

        // try to get number of the users
        $invoicesCountBefore = $invoiceRepository->count([]);

        // make an invoice
        $lineItems = '[{"description":"Apple Macbook Pro","quantity":1,"unit_price":2899,"vat":20}]';
        for ($i = 0; $i < 5; $i++) {
            $invoiceRepository->createInvoice($entityGenerator->getUser()->getId(),
                $entityGenerator->getCompany()->getId(),
                $faker->title,
                $faker->name,
                $faker->buildingNumber,
                $faker->name,
                "EURO",
                $lineItems);

        }

        // now, get the new count
        $newInvoicesCount = $invoiceRepository->count([]);

        // check for the count
        $this->assertEquals(5, $newInvoicesCount - $invoicesCountBefore);

        // now, we need to add new invoice then we have to get the exception
        $this->expectException(DebtorLimitException::class);
        $invoiceRepository->createInvoice($entityGenerator->getUser()->getId(),
            $entityGenerator->getCompany()->getId(),
            $faker->title,
            $faker->name,
            $faker->buildingNumber,
            $faker->name,
            "EURO",
            $lineItems);

    }

}