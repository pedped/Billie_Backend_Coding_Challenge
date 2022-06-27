<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Helpers\StringHelper;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Helper\Helper;

class UserControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private \Doctrine\ORM\EntityManager $entityManager;

    public function test_create_user(): void
    {

        // make a client
        $client = static::createClient();

        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        // get repository
        $userRepository = $this->entityManager->getRepository(User::class);


        // try to get number of the users
        $usersCount = $userRepository->count([]);

        // get faker
        $faker = \Faker\Factory::create();

        // request to page
        $client->request('POST', '/user/add_user', [
            "first_name" => $faker->firstName,
            "last_name" => $faker->lastName,
            "password" => $faker->randomLetter,
            "email" => $faker->email
        ]);

        // now, get the new count
        $newUsersCount = $userRepository->count([]);

        // Validate a successful response and some content
        $values = json_decode(json_decode($client->getResponse()->getContent(), true), true);

        // check for the count
        $this->assertCount(1, $newUsersCount - $usersCount);

        // check if all values exists
        $this->assertArrayHasKey("userId", $values);
    }

}