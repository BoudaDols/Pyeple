<?php

namespace App\Repository;

use App\Entity\IpToCountry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IpToCountry|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpToCountry|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpToCountry[]    findAll()
 * @method IpToCountry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpToCountryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IpToCountry::class);
    }

    // /**
    //  * @return IpToCountry[] Returns an array of IpToCountry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IpToCountry
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
