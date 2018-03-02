<?php

namespace App\Repository;

use App\Entity\BasketItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BasketItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BasketItem::class);
    }


    public function findPendingByUserId($userId)
    {
        return $this->createQueryBuilder('bi')
            ->where('bi.userId = :userId')
            ->andWhere('bi.status = :status')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'PENDING')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPendingByUserIdAndProduct($userId, $productId)
    {
        return $this->createQueryBuilder('bi')
            ->where('bi.userId = :userId')
            ->andWhere('bi.status = :status')
            ->andWhere('bi.product = :productId')
            ->setParameter('userId', $userId)
            ->setParameter('status', 'PENDING')
            ->setParameter('productId', $productId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}
