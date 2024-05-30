<?php

namespace App\Repository;

use App\Entity\ItemCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ItemCollection>
 */
class ItemCollectionRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, ItemCollection::class);
    }

    public function getItemCollectionWithCustomAttributes(int $collectionId)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.customItemAttributes', 'ca')
            ->where('c.id = :collectionId')
            ->setParameter('collectionId', $collectionId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getCategoriesWithItemCollections(array $collectionIds)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.category', 'c')
            ->leftJoin('i.user', 'u')
            ->addSelect('u')
            ->addSelect('c')
            ->where('i.id IN (:collectionIds)')
            ->setParameter('collectionIds', $collectionIds)
            ->getQuery()
            ->getResult();
    }
}
