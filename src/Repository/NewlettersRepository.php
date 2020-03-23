<?php

namespace App\Repository;

use App\Entity\Newletters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Newletters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Newletters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Newletters[]    findAll()
 * @method Newletters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewlettersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newletters::class);
    }

    // /**
    //  * @return Newletters[] Returns an array of Newletters objects
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
    public function findOneBySomeField($value): ?Newletters
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
