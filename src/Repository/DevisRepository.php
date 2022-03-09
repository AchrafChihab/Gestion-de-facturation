<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function findMaxCode($year, $prefix)
    {
        $qb = $this->createQueryBuilder('entity');

        $qb->where($qb->expr()->like('entity.nom', ':nom'))
            ->setParameter('nom', '%' . $prefix . $year . '%');
        $qb->select($qb->expr()->max('entity.nom'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function Findbyone($client)
    {
        return $this->createQueryBuilder('f')
            ->LeftJoin('f.client','c')
            ->Where('f.client = :id')
            ->setParameter('id', $client)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Devis[] Returns an array of Devis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Devis
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
