<?php

namespace App\Repository;

use App\Entity\Activechatroom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Activechatroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activechatroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activechatroom[]    findAll()
 * @method Activechatroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivechatroomRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Activechatroom::class);
    }

    // /**
    //  * @return Activechatroom[] Returns an array of Activechatroom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activechatroom
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
