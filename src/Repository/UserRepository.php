<?php

namespace App\Repository;

use App\Entity\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Helpers\PasswordHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * add new user to database
     * @param User $entity
     * @param bool $flush
     * @return void
     * @throws \Exception
     */
    public function add(User &$entity, bool $flush = false): void
    {

        // check if user with that email exists before in database
        if ($this->count(["email" => $entity->getEmail()]) > 0) {
            throw new \Exception('User with this email exist before!');
        }

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * generate admin user
     * @param User $user
     * @return void
     * @throws \Exception
     */
    public function generateAdminUser(User &$user): void
    {
        // first, check if we have admin user, do not create a new one
        $adminCount = $this->count([
            "role" => UserRole::$ADMIN
        ]);
        if ($adminCount > 0) {
            throw new \Exception('Admin user already exist');
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
