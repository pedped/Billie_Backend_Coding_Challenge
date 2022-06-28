<?php

namespace App\Tests\WebTest\Controller;

use App\Entity\Invoice;
use App\Tests\Helper\EntityGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceRepositoryTest extends WebTestCase
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
        $entityGenerator->generateUser()->generateUserToken()->generateCompany();

        // get repository
        $invoiceRepository = $entityManager->getRepository(Invoice::class);

        // try to get number of the users
        $invoicesCountBefore = $invoiceRepository->count([]);

        // make an invoice

        $lineItems = '[{"description":"Apple Macbook Pro","quantity":1,"unit_price":2899,"vat":20}]';
        $invoiceRepository->createInvoice($entityGenerator->getUser()->getId(),
            $entityGenerator->getCompany()->getId(),
            $faker->title,
            $faker->name,
            $faker->buildingNumber,
            $faker->name,
            "EURO",
            $lineItems);


        // now, get the new count
        $newInvoicesCount = $invoiceRepository->count([]);

        // check for the count
        $this->assertEquals(1, $newInvoicesCount - $invoicesCountBefore);

    }


}