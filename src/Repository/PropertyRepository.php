<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findSearch(SearchData $search)
    {
        $query = $this->createQueryBuilder('t');
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

    public function getCount(SearchData $searchData){
        count($this->findSearch($searchData));
    }

    public function findAllDesc()
    {
        return $this->createQueryBuilder('t')
                    ->orderBy('t.id' , 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}
