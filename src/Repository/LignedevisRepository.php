<?php

namespace App\Repository;

use App\Entity\Lignedevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignedevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignedevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignedevis[]    findAll()
 * @method Lignedevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignedevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignedevis::class);
    }

    // /**
    //  * @return Lignedevis[] Returns an array of Lignedevis objects
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
    public function findOneBySomeField($value): ?Lignedevis
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
