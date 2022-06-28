<?php

namespace App\Tests\WebTest\Controller;

use App\Entity\Invoice;
use App\Tests\Helper\EntityGenerator;
use App\Texts\ResponseMessages;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{

    public function test_create_invoice(): void
    {

        // make a client
        $client = static::createClient();

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
        $invoiceCountBefore = $invoiceRepository->count([]);

        // request to page
        $client->request('POST', '/invoice/add', [
            "auth_user_id" => $entityGenerator->getUser()->getId(),
            "auth_user_token" => $entityGenerator->getUserToken()->getToken(),
            "company_id" => $entityGenerator->getCompany()->getId(),
            "title" => $faker->title,
            "summery" => $faker->name,
            "vat_number" => $faker->buildingNumber,
            "terms" => $faker->name,
            "line_items" => '[{"description":"Apple Macbook Pro","quantity":1,"unit_price":2899,"vat":20}]',
            "currency" => "EURO",
        ]);

        // now, get the new count
        $newInvoiceCount = $invoiceRepository->count([]);

        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check for the count
        $this->assertEquals(1, $newInvoiceCount - $invoiceCountBefore);

        // check if all values exists
        $this->assertArrayHasKey("invoice_id", $values);
        $this->assertArrayHasKey("invoice_number", $values);
    }


    public function test_create_invoice_as_payed(): void
    {

        // make a client
        $client = static::createClient();

        $kernel = self::bootKernel();
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // get faker
        $faker = \Faker\Factory::create();

        // get entities
        $entityGenerator = new EntityGenerator($entityManager);
        $entityGenerator->generateUser()->generateUserToken()->generateCompany()->generateInvoice();

        // get repository
        $invoiceRepository = $entityManager->getRepository(Invoice::class);

        // request to page
        $client->request('POST', '/invoice/set_payed', [
            "auth_user_id" => $entityGenerator->getUser()->getId(),
            "auth_user_token" => $entityGenerator->getUserToken()->getToken(),
            "invoice_id" => $entityGenerator->getInvoice()->getId(),
        ]);


        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check if all values exists
        $this->assertArrayHasKey("message", $values);
        $this->assertEquals(ResponseMessages::$INVOICE_PAYED_SUCCESSFULLY, $values["message"]);
    }

}