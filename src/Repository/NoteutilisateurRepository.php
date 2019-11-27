<?php

namespace App\Repository;

use App\Entity\Noteutilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Noteutilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noteutilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noteutilisateur[]    findAll()
 * @method Noteutilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteutilisateurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Noteutilisateur::class);
    }

    // /**
    //  * @return Noteutilisateur[] Returns an array of Noteutilisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Noteutilisateur
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
