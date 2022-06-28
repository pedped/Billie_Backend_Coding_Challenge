<?php

namespace App\Tests\Helper;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\Token;
use App\Entity\User;
use App\Enums\CompanyStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Helpers\PasswordHelper;
use App\Helpers\StringHelper;
use Doctrine\ORM\EntityManager;

class EntityGenerator
{

    /**
     * base entity manager
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * this faker can be used to generate fake classes
     * @var \Faker\Generator
     */
    private \Faker\Generator $faker;

    /**
     * generated user
     * @var User
     */
    private User $user;

    /**
     * this is plain password used to generate a user
     * @var string
     */
    private string $plainUserPassword;

    /**
     * generated user token
     * @var Token
     */
    private Token $userToken;

    /**
     * generated company
     * @var Company
     */
    private Company $company;

    /**
     * generated invoice
     * @var Invoice
     */
    private Invoice $invoice;


    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->faker = \Faker\Factory::create();
    }


    /**
     * generate a user
     * @return EntityGenerator
     */
    public function generateUser(): EntityGenerator
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        // get email
        $email = $this->faker->email;
        $password = $this->faker->password;

        // make a new user
        $user = new User();
        $user->setStatus(UserStatus::$ACTIVE);
        $user->setEmail($email);
        $user->setRole(UserRole::$NORMAL);
        $user->setFirstName($this->faker->firstName);
        $user->setLastName($this->faker->lastName);

        // Hash a plain password;
        $hashedPassword = PasswordHelper::hash($password);
        $user->setPassword($hashedPassword);

        // now, add the user
        $userRepository->add($user, true);

        // set plain user password
        $this->plainUserPassword = $password;

        // set user
        $this->user = $user;

        // send back this class
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainUserPassword(): string
    {
        return $this->plainUserPassword;
    }

    public function generateUserToken()
    {
        $tokenRepository = $this->entityManager->getRepository(Token::class);


        // make a new userToken
        $userToken = new Token();
        $userToken->setUserId($this->getUser()->getId());
        $userToken->setToken(StringHelper::generateRandomString(24));

        // set the date that this token is valid
        $date = new \DateTime('@' . (time() + 3600 * 24 * 30));
        $userToken->setValidUntil($date);

        // now, add the userToken
        $tokenRepository->add($userToken, true);

        $this->userToken = $userToken;

        // send back this class
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Token
     */
    public function getUserToken(): Token
    {
        return $this->userToken;
    }

    /**
     * this will create a company
     * @param int $debtLimit
     * @return EntityGenerator
     */
    public function generateCompany(int $debtLimit = 10)
    {
        $companyRepository = $this->entityManager->getRepository(Company::class);

        // create admin user
        $company = new Company();
        $company->setUserId($this->getUser()->getId());
        $company->setName($this->faker->company);
        $company->setAddress($this->faker->address);
        $company->setPhoneNumber($this->faker->phoneNumber);
        $company->setVatNumber($this->faker->buildingNumber);
        $company->setStatus(CompanyStatus::$ACTIVE);

        // set default debtor limit
        $company->setDebtorLimit($debtLimit);

        // set company
        $this->company = $company;

        // add item
        $companyRepository->add($company, true);

        // send back this class
        return $this;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    public function generateInvoice()
    {
        $invoiceRepository = $this->entityManager->getRepository(Invoice::class);


        $lineItems = '[{"description":"Apple Macbook Pro","quantity":1,"unit_price":2899,"vat":20}]';


        $this->invoice = $invoiceRepository->createInvoice($this->user->getId(),
            $this->company->getId(),
            $this->faker->title,
            $this->faker->text,
            $this->faker->buildingNumber,
            $this->faker->text,
            "EURO",
            $lineItems);


        // send back this class
        return $this;
    }

    /**
     * @return Invoice
     */
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }


}