<?php

namespace App\Repository;

use App\Entity\Post;
use App\Classe\Search;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void
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
    public function remove(Post $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getPaginatedPosts(int $page, int $limit): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('p')
                ->addSelect('c')
                ->join('p.comments', 'c')
                ->setMaxResults($limit)
                ->setFirstResult(($page - 1) * $limit)
        );
    }
    /**
     * @return Post[] Returns an array of Post objects
     */

    public function findPostsWithCommentsAndTags()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :value')
            ->setParameter('value', true)
            ->orderBy('p.publishedAt', 'DESC')
            ->leftjoin('p.comments', 'c')
            ->addSelect('c')
            ->leftjoin('p.tags', 't')
            ->addSelect('t')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Post[] Returns an array of Tag objects
     */
    public function findPostsByTag($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isPublished = :value')
            ->setParameter('value', true)
            ->leftjoin('p.tags', 't')
            ->addSelect('t')
            ->andWhere('t.id = :val')
            ->setParameter('val', $value)
            ->orderBy('p.publishedAt', 'DESC')

            ->getQuery()
            ->getResult();
    }

    public function findWithSearch(Search $search)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :val')
            ->orWhere('p.content LIKE :val')
            ->setParameter('val', "%{$search->string}%")
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }



    /*
    public function findOneBySomeField($value): ?Post
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
