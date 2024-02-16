<?php

namespace App\Repository;

use App\Entity\Brand;
use App\Entity\Product;
use Doctrine\ORM\Query;
use App\Entity\Category;
use App\Entity\ProductFilter;
use Doctrine\ORM\ORMException;
use Vico\Attachment\ProductAttachment;
use Doctrine\ORM\OptimisticLockException;
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
    
    public $qSearchFields = ['title'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * plus tard param ProductFilters
     */
    public function findAllQuery():Query
    {
        return $this->createQueryBuilder('p')
                    ->getQuery();
    }

    public function findFilteredQuery(ProductFilter $filter):Query
    {
        $queryBuilder = $this->createQueryBuilder('p');
        if(!empty($filter->brand))
        {
            $queryBuilder->andWhere('p.brand = :brand')
                        ->setParameter('brand', $filter->brand);
        }
        if(!empty($filter->category))
        {
            $queryBuilder->andWhere('p.category = :category')
                        ->setParameter('category', $filter->category);
        }
        if(!empty($filter->price_min))
        {
            $queryBuilder->andWhere('p.price > :price_min')
                        ->setParameter('price_min', $filter->price_min);
        }
        if(!empty($filter->price_max))
        {
            $queryBuilder->andWhere('p.price < :price_max')
                        ->setParameter('price_max', $filter->price_max);
        }
        if(!empty($filter->price_order) AND in_array($filter->price_order, ['asc', 'desc']))
        {
            $queryBuilder->orderBy('p.price', $filter->price_order);
        }
        if(!empty($filter->qSearch))
        {
            $queryBuilder->join('p.category', 'c')
                        ->andWhere('c.name LIKE :q OR p.title LIKE :q')
                        ->setParameter('q', '%'.$filter->qSearch.'%');
        }

        return $queryBuilder->getQuery();
    }



    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        ProductAttachment::delete($entity);
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
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

    private function filteredQuery(Object $filter):Query
    {
        $queryBuilder = $this->createQueryBuilder('p');
        foreach($filter as $property => $value)
        {
            if($value !== null)
            {
                $field = 'p.';
                if(str_contains($property, '_min'))
                {
                    $field .= str_replace('_min', '', $property);
                    $queryBuilder->andWhere($field.' > :'.$property)
                                ->setParameter($property, $value);
                }
                elseif(str_contains($property, '_max'))
                {
                    $field .= str_replace('_max', '', $property);
                    $queryBuilder->andWhere($field.' < :'.$property)
                                ->setParameter($property, $value);
                }
                elseif(str_contains($property, '_order'))
                {
                    if(in_array($value, ['asc', 'desc']))
                    {
                        $field .= str_replace('_order', '', $property);
                        $queryBuilder->orderBy($field, $value);
                    }
                }
                elseif($property === 'qSearch')
                {
                    foreach($this->qSearchFields as $property)
                    {
                        $queryBuilder->orWhere('p.'.$property.' LIKE :q');
                    }
                    $queryBuilder->setParameter('q', '%'.$value.'%');
                }
                else
                {
                    $queryBuilder->andWhere('p.'.$property.' = :'.$property)
                                ->setParameter($property, $value);
                }
            }
        }
        return $queryBuilder->getQuery();
    }
}
