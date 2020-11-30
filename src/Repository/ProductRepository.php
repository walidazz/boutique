<?php

namespace App\Repository;

use App\Entity\Search;
use App\Entity\Product;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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


    public function findWithSearch(Search $search)
    {
        $query = $this->createQueryBuilder('p')
            ->select('c', 'p')
            ->join('p.category', 'c');
        if (!empty($search->getCategory())) {
            $query = $query->andWhere('c.id  IN (:category)')
                ->setParameter('category', $search->getCategory());
        }

        // if (!empty($search->getString())) {
        //     $query = $query->andWhere('p.name LIKE :string')
        //         ->setParameter('string', "%{$search->getString()}%");
        // }
        if (!empty($search->getString())) {
            $query = $query->andWhere('MATCH_AGAINST(p.name,p.subtitle,p.description) AGAINST(:string boolean)>0')
                ->setParameter('string', $search->getString());
        }
        return $query->getQuery()
            // ->getResult()
        ;
    }



    public function search($mots): Query
    {
        return $this->createQueryBuilder('p')
            ->where('MATCH_AGAINST(p.name,p.subtitle,p.description) AGAINST(:mots boolean)>0')
            ->setParameter('mots', $mots)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();
    }
}
