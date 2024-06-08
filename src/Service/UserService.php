<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security               $security,
    )
    {
    }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function saveCollection($token): void
    {
        $user = $this->security->getUser();
        $user->setApiToken($token);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
