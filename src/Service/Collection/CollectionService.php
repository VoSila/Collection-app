<?php

namespace App\Service\Collection;

use App\Entity\User;
use App\Form\CollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CollectionService
{
    public function __construct(
        private readonly Security               $security,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function update(array $userIds): void
    {

    }
}
