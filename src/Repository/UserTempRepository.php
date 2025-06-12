<?php

namespace App\Repository;

use App\Entity\Usertemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usertemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usertemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usertemp[]    findAll()
 * @method Usertemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTempRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usertemp::class);
    }

    // /**
    //  * @return Usertemp[] Returns an array of Usertemp objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Usertemp
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
