<?php

namespace App\Repository;

use App\Entity\Token;
use App\Helpers\PasswordHelper;
use App\Helpers\StringHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @extends ServiceEntityRepository<Token>
 *
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    private UserRepository $userRepository;

    public function __construct(ManagerRegistry $registry, UserRepository $userRepository)
    {
        parent::__construct($registry, Token::class);
        $this->userRepository = $userRepository;
    }

    public function add(Token $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Token $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * will generate a token based on the email and password
     * @param string $email
     * @param string $password
     * @return Token
     * @throws Exception
     */
    public function generateToken(string $email, string $password , $tokenValidPeriod): Token
    {

        // load the user
        $user = $this->userRepository->findOneBy([
            'email' => $email,
        ]);

        // check if the user exist
        if (!$user) {
            throw new Exception('Invalid email/password');
        }


        // Hash a plain password;
        $passwordVerified = PasswordHelper::verify($user->getPassword(), $password);
        if (!$passwordVerified) {
            throw new Exception('Invalid email/password');
        }


        // email and password are verified, we have to generate a new token and send back the result to user
        $userToken = new Token();
        $userToken->setToken(StringHelper::generateRandomString(64));
        $userToken->setUserId($user->getId());

        // set the date that this token is valid
        $date = new \DateTime('@' . (time() + 3600 * 24 * $tokenValidPeriod));
        $userToken->setValidUntil($date);

        $this->getEntityManager()->persist($userToken);
        $this->getEntityManager()->flush();

        return $userToken;
    }
}
