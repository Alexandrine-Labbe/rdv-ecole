<?php

namespace App\Repository;

use App\Entity\Child;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Child>
 */
class ChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Child::class);
    }

        /**
         * @return Child[] Returns an array of Child objects
         */
        public function findByTeacher(Uuid $id_teacher): array
        {
            return $this->createQueryBuilder('c')
                ->join('c.appointments', 'a')
                ->andWhere('a.teacher = :id_teacher')
                ->setParameter('id_teacher', $id_teacher)
                ->orderBy('c.last_name', 'ASC')
                ->addOrderBy('c.first_name', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Child
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
