<?php

namespace App\Repository;

use App\Entity\Likecomment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Likecomment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likecomment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likecomment[]    findAll()
 * @method Likecomment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikecommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Likecomment::class);
    }

    // /**
    //  * @return Likecomment[] Returns an array of Likecomment objects
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
    public function findOneBySomeField($value): ?Likecomment
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
