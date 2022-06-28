<?php

namespace App\Tests\WebTest\Controller;

use App\Entity\Token;
use App\Tests\Helper\EntityGenerator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TokenControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    public function test_create_token_which_email_not_exists(): void
    {

        // make a client
        $client = static::createClient();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        // get repository
        $tokenRepository = $this->entityManager->getRepository(Token::class);


        // try to get number of the users
        $usersCount = $tokenRepository->count([]);

        // get faker
        $faker = \Faker\Factory::create();

        // request to page
        $client->request('POST', '/user/add_user', [
            "first_name" => $faker->email,
            "last_name" => $faker->password,
        ]);

        // now, get the new count
        $newUsersCount = $tokenRepository->count([]);

        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check for the count
        $this->assertEquals(0, $newUsersCount - $usersCount);

        // check if all values exists
        //$this->assertArrayHasKey("userId", $values);
    }


    public function test_create_token_which_email_exist(): void
    {

        // make a client
        $client = static::createClient();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // get faker
        $entityGenerator = new EntityGenerator($this->entityManager);
        $entityGenerator->generateUser();


        // get repository
        $tokenRepository = $this->entityManager->getRepository(Token::class);

        // try to get number of the users
        $tokensCount = $tokenRepository->count([]);

        // request to page
        $client->request('POST', '/user/generate_token', [
            "first_name" => $entityGenerator->getUser()->getEmail(),
            "last_name" => $entityGenerator->getPlainUserPassword(),
        ]);

        // now, get the new count
        $newTokensCount = $tokenRepository->count([]);

        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check for the count
        $this->assertEquals(1, $newTokensCount - $tokensCount);

        // check if all values exists
        $this->assertArrayHasKey("user_id", $values);
        $this->assertArrayHasKey("token", $values);
    }


}