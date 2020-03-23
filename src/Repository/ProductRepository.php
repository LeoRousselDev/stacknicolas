<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Query
     */
    public function findAllVisible(PropertySearch $search): Query //fonction qui affichera les produits dans le catalogue
    {
        $query = $this->findVisibleQuery();

        if ($search->getMaxPrice()) { //Si l'utilisateur recherche un prix max
            $query = $query->andWhere('p.price <= :maxprice'); //Spécifie une ou plusieurs restrictions au résultat de la requête.
            $query->setParameter('maxprice', $search->getMaxPrice()); //Définit un paramètre de requête pour la requête en cours de construction.
        }
        if ($search->getSelectedCategory()) { //Si l'utilisateur recherche une categorie precise
            $query = $query->andWhere('p.productCategory = :selectedcategory');
            $query->setParameter('selectedcategory', $search->getSelectedCategory());
        }
        if ($search->getSearchbar()) { //Si l'utilisateur recherche
            $query = $query->andwhere('p.libelle = :searchbar');
            $query->setParameter('searchbar', $search->getSearchbar());
        }

        if ($search->getOrderBy1() == 1) { //tri par prix croissant
            $query->orderBy('p.price', 'ASC'); //Spécifie un ordre pour les résultats de la requête
        }
        if ($search->getOrderBy1() == 2) { //decroissant
            $query->orderBy('p.price', 'DESC');
        }
        if ($search->getOrderBy1() == 3) { //tri par date ancien au recent
            $query->orderBy('p.createdAt', 'ASC');
        }
        if ($search->getOrderBy1() == 4) { //recent au ancien
            $query->orderBy('p.createdAt', 'DESC');
        }
        if ($search->getOrderBy1()) { //meilleurs notes
            $query->orderBy('p.stars', 'DESC');
        }

        return $query->getQuery(); //Construit une instance d'une requête
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p') //Construction d'une requete
        ->where('p.stock != 0'); //Retire les produits qui ne sont plus en stock
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
