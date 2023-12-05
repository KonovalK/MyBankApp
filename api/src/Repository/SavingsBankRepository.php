<?php

namespace App\Repository;

use App\Entity\SavingsBank;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavingsBank>
 *
 * @method SavingsBank|null find($id, $lockMode = null, $lockVersion = null)
 * @method SavingsBank|null findOneBy(array $criteria, array $orderBy = null)
 * @method SavingsBank[]    findAll()
 * @method SavingsBank[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SavingsBankRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavingsBank::class);
    }

    public function findSavingsBanks($sortField, $sortOrder, $userId)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where('s.user = :userId')
            ->setParameter('userId', $userId);

        if ($sortField && $sortOrder) {
            $queryBuilder->orderBy('s.' . $sortField, $sortOrder);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
}
