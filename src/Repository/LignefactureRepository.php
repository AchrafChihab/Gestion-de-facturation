<?php

namespace App\Repository;

use App\Entity\Lignefacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lignefacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lignefacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lignefacture[]    findAll()
 * @method Lignefacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignefactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lignefacture::class);
    }

    public function findlignefacture($id)
    {
        return $this->createQueryBuilder('L')
                   ->leftJoin('L.factures', 'F')
                   ->where('F = :id')
                   ->setParameter('id', $id)
                   ->getQuery()
                   ->getResult();
    }

    // /**
    //  * @return Lignefacture[] Returns an array of Lignefacture objects
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
    public function findOneBySomeField($value): ?Lignefacture
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
