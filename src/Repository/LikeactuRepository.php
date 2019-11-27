<?php

namespace App\Repository;

use App\Entity\Likeactu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Likeactu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likeactu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likeactu[]    findAll()
 * @method Likeactu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeactuRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Likeactu::class);
    }

    // /**
    //  * @return Likeactu[] Returns an array of Likeactu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Likeactu
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
