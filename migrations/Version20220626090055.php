<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\PlaintextPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220626090055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'this migration will create some sample data to database';
    }

    public function up(Schema $schema): void
    {
//        // this up() migration is auto-generated, please modify it to your needs
//        $passwordHasher = new PlaintextPasswordHasher();
//        $entityManager = $doctrine->getManager();
//
//        // create admin user
//        $adminUser = new User();
//        $adminUser->setFirstName("Ata");
//        $adminUser->setLastName("Zangene");
//        $adminUser->setRole(UserRole::$ADMIN);
//        $adminUser->setStatus(UserStatus::$ACTIVE);
//        $adminUser->setPassword($passwordHasher->hashPassword(env("ADMIN_PASSWORD")));





    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
