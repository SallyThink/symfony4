<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @return mixed
     */
    public function findAllWithMaxLengthComment()
    {
        return $this->createQueryBuilder('t')
            ->addSelect('LENGTH(c.text) AS comment_max_length')
            ->addSelect([
                'c.id as comment_id',
                'u.id as user_id',
                'u.email as user_email',
            ])
            ->leftJoin(Comment::class, 'c', \Doctrine\ORM\Query\Expr\Join::WITH, 't.id = c.task')
            ->leftJoin(User::class, 'u', \Doctrine\ORM\Query\Expr\Join::WITH, 'c.user = u.id')
            ->addOrderBy('t.id')
            ->addOrderBy('comment_max_length')
            ->getQuery()
            ->getResult()
            ;
    }
}
