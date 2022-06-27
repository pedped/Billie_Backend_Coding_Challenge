<?php

namespace App\Helpers;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class PasswordHelper
{
    /**
     * this function will try to hash a password
     * @param $plainPassword
     * @return string
     */
    public static function hash($plainPassword): string
    {
        // make a password
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        // Retrieve the right password hasher by its name
        $passwordHasher = $factory->getPasswordHasher('common');

        // plain password
        return $passwordHasher->hash($plainPassword);
    }

    /**
     * this function will verify the password is the same as hashed one
     * @param mixed $hashedPassword
     * @param $plainPassword
     * @return bool
     */
    public static function verify(mixed $hashedPassword, $plainPassword): bool
    {
        // make a password
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        // Retrieve the right password hasher by its name
        $passwordHasher = $factory->getPasswordHasher('common');

        // plain password
        return $passwordHasher->verify($hashedPassword, $plainPassword);
    }
}