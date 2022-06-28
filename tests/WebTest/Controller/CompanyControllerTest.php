<?php

namespace App\Tests\WebTest\Controller;

use App\Entity\Company;
use App\Tests\Helper\EntityGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyControllerTest extends WebTestCase
{

    public function test_create_company(): void
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
        $entityGenerator->generateUser()->generateUserToken();

        // get repository
        $companyRepository = $entityManager->getRepository(Company::class);

        // try to get number of the users
        $companyCountBefore = $companyRepository->count([]);

        // request to page
        $client->request('POST', '/company/add', [
            "auth_user_id" => $entityGenerator->getUser()->getId(),
            "auth_user_token" => $entityGenerator->getUserToken()->getToken(),
            "name" => $faker->company,
            "address" => $faker->address,
            "phone_number" => $faker->phoneNumber,
            "vat_number" => $faker->buildingNumber,
        ]);

        // now, get the new count
        $newCompaniesCount = $companyRepository->count([]);

        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check for the count
        $this->assertEquals(1, $newCompaniesCount - $companyCountBefore);

        // check if all values exists
        $this->assertArrayHasKey("user_id", $values);
        $this->assertArrayHasKey("token", $values);
    }


}