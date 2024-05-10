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
    public function findByCollectionId($collectionId)
    {
        return $this->createQueryBuilder('c')
            ->join('c.itemCollection', 'ic')
            ->andWhere('ic.id = :collectionId')
            ->setParameter('collectionId', $collectionId)
            ->getQuery()
            ->getResult();
    }
}
