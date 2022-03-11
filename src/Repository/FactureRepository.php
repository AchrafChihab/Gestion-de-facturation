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
            ->LeftJoin('f.client','c')
            ->Where('f.client = :id')
            ->setParameter('id', $client)
            ->getQuery()
            ->getResult()
        ;
    }
    public function totalfactureclient($client)
    {
        return $this->createQueryBuilder('f')
            ->LeftJoin('f.client','c')
            ->Where('f.client = :id')
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

    public function getCountFactureBy($clientid,$statue_nom){

        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->Join('f.client','c')
            ->Join('f.statue','s')
            ->Where('f.client = c')
            ->AndWhere('c.id = :clientid')
            ->AndWhere('s.nom = :statue_nom')
            ->setParameters(['clientid' => $clientid,'statue_nom' => $statue_nom])
            ->getQuery()
            ->getSingleScalarResult()
        ;

    }

    public function getFactureAnnuler($statue_nom){

          return $this->createQueryBuilder('f')
            ->Join('f.statue','s')
            ->AndWhere('s.nom = :statue_nom')
            ->setParameters(['statue_nom' => $statue_nom])
            ->getQuery()
            ->getScalarResult()
        ;

    }

    public function getAllByDesc()
    {
        return $this->createQueryBuilder('F') 
            ->orderBy('F.createdAt', 'DESC')
            ->setMaxResults(200)
            ->getQuery()
            ->getResult()
        ;
    } 

    public function getLastesByDesc()
    {
        return $this->createQueryBuilder('F') 
            ->orderBy('F.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
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
