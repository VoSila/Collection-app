<?php

namespace App\Repository;

use App\Entity\CustomItemAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomItemAttribute>
 */
class CustomItemAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomItemAttribute::class);
    }
    public function findBySomeCriteria($name)
    {
        return $this->createQueryBuilder('cia')
            ->andWhere('cia.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
