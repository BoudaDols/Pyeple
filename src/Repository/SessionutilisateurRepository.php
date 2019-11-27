<?php

namespace App\Repository;

use App\Entity\Sessionutilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sessionutilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sessionutilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sessionutilisateur[]    findAll()
 * @method Sessionutilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionutilisateurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sessionutilisateur::class);
    }

    // /**
    //  * @return Sessionutilisateur[] Returns an array of Sessionutilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sessionutilisateur
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
