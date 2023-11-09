<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HasherService
{
    private $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Hash the password
     */ 
    public function hashPassword($user)
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    }
}