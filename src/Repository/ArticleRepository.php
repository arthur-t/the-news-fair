<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }


    public function findByPage($page,$number=5)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.publicationDate', 'DESC')
            ->setMaxResults($number)
            ->setFirstResult($number*($page-1))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTitle($search)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.title LIKE :search')
            ->orderBy('a.publicationDate', 'DESC')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNumber()
    {
        return $this->createQueryBuilder('a')
            ->select("count(a)")
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
