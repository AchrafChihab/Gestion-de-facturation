<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

    // /**
    //  * @return Facture[] Returns an array of Facture objects
    //  */
    
    public function Findbyone($client)
    {
        return $this->createQueryBuilder('f')
            ->LeftJoin('f.client_id','c')
            ->Where('f.client_id = :id')
            ->setParameter('id', $client)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMaxCode($year, $prefix)
    {
        $qb = $this->createQueryBuilder('entity');

        $qb->where($qb->expr()->like('entity.nom', ':nom'))
            ->setParameter('nom', '%' . $prefix . $year . '%');
        $qb->select($qb->expr()->max('entity.nom'));

        return $qb->getQuery()->getOneOrNullResult();
    }


    // public function findlignefacture($lignefacture)
    // {
    //     return $this->createQueryBuilder('f')
    //         ->Join('f.lignefacture', 'l')
    //         ->where('f.lignefacture = l')
    //         // ->Where('f.lignefacture = l.id')
    //         // ->AndWhere('f.lignefacture = :id')
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?Facture
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
