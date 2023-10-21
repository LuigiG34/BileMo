<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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
     * Cette méthode retourne $limit à partir de la page $page.
     *
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function findAllWithPagination(int $page, int $limit) {
        $qb = $this->createQueryBuilder('p')
            ->setFirstResult(($page -1) * $limit)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
