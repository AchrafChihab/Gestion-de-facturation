<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
    // /**
    //  * @return Devis[] Returns an array of Devis objects
    //  */
    public function getAllByDesc()
    {
        return $this->createQueryBuilder('D') 
            ->orderBy('D.createdAt', 'DESC')
            ->setMaxResults(200)
            ->getQuery()
            ->getResult()
        ;
    } 



    public function getDevis($statue_nom){

        return $this->createQueryBuilder('D')
          ->Join('D.statue','s')
          ->Where('s.nom != :statue_nom')
          ->setParameters(['statue_nom' => $statue_nom])
          ->orderBy('D.createdAt', 'DESC')
          ->setMaxResults(200)
          ->getQuery()
          ->getResult()
      ;

  }

  
  // Function to find the difference 
  // between two dates.
  function dateDiffInDays($datede, $datea) 
  {
      // Calculating the difference in timestamps
      $diff = strtotime($datea) - strtotime($datede);
  
      // 1 day = 24 hours
      // 24 * 60 * 60 = 86400 seconds
      return abs(round($diff / 86400));

  }
  



    public function getDevisAnnuler($statue_nom){

        return $this->createQueryBuilder('D')
          ->Join('D.statue','s')
          ->Where('s.nom = :statue_nom')
          ->setParameters(['statue_nom' => $statue_nom])
          ->orderBy('D.createdAt', 'DESC')
          ->setMaxResults(200)
          ->getQuery()
          ->getResult()
      ;

  }
    public function getLastesByDesc()
    {
        return $this->createQueryBuilder('D') 
            ->orderBy('D.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    } 
 


    

}
