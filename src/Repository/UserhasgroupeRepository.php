<?php

namespace App\Repository;

use App\Entity\Userhasgroupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Userhasgroupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userhasgroupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userhasgroupe[]    findAll()
 * @method Userhasgroupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserhasgroupeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Userhasgroupe::class);
    }

    // /**
    //  * @return Userhasgroupe[] Returns an array of Userhasgroupe objects
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
    public function findOneBySomeField($value): ?Userhasgroupe
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
