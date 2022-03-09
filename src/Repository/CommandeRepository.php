<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
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
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
