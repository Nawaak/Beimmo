<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param SearchData $search
     * @return int|mixed|string
     */
    public function findSearch(SearchData $search)
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.online = 1');
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('t.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        if (!empty($search->min)) {
            $query = $query
                ->andWhere('t.price >= :min')
                ->setParameter('min', $search->min);
        }
        if (!empty($search->max)) {
            $query = $query
                ->andWhere('t.price <= :max')
                ->setParameter('max', $search->max);
        }
        if (!empty($search->room)) {
            $query = $query
                ->andWhere('t.room IN(:room)')
                ->setParameter('room', $search->room);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @return array|int|mixed|string
     */
    public function findAll()
    {
        return $this->createQueryBuilder('t')
            ->where('t.online = 1')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findAllDesc()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findPropertyOnlineByUser($user)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter(':user', $user)
            ->andWhere('t.online = 1')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $user
     * @return int|mixed|string
     */
    public function findPropertyOfflineByUser($user)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter(':user', $user)
            ->andWhere('t.online = 0')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
