<?php

namespace App\Repository;

use App\Entity\Pyepe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pyepe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pyepe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pyepe[]    findAll()
 * @method Pyepe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PyepeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pyepe::class);
    }

    // /**
    //  * @return Pyepe[] Returns an array of Pyepe objects
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
    public function findOneBySomeField($value): ?Pyepe
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
