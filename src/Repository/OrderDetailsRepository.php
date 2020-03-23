<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetails::class);
    }

     /**
     * @return QueryBuilder
     */
    public function findSoldStat(): QueryBuilder
    {
        return $query = $this->createQueryBuilder('s')
            ->select('SUM(s.Quantity) as Total, p.id as ID, p.libelle as ProductName')
            ->join('s.Product','p')
            ->groupBy('p.id')
            ->orderBy('Total', 'DESC');
    }

    /**
     *
     */
    public function findBestSales()
    {
        $bestsales = $this->findSoldStat()
            ->setMaxResults(3);

        return $bestsales->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findMonthlySales($id)
    {
        $monthlysales = $this->createQueryBuilder('s')
            ->select('SUM(s.Quantity) as Total, p.id as ID, p.libelle as ProductName, MONTH(o.Date) as Mois')
            ->join('s.Product', 'p')
            ->Join('s.Orders', 'o')
            ->where('s.Orders = o.id and p.id = :id')
            ->setParameter(':id', $id)
            ->groupBy('p.id')
            ->addGroupBy('Mois');

        return $monthlysales->getQuery()
            ->getResult();
    }



    // /**
    //  * @return OrderDetails[] Returns an array of OrderDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderDetails
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
