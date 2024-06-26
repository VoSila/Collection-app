<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findWithEagerLoading($id)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.tags', 't')
            ->addSelect('t')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopSixLargestItemCollectionIds()
    {
        return $this->createQueryBuilder('i')
            ->select('IDENTITY(i.itemCollection) as itemCollectionId, COUNT(i.id) as itemCount')
            ->groupBy('i.itemCollection')
            ->orderBy('itemCount', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findLastSixWithEagerLoading()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }
}
