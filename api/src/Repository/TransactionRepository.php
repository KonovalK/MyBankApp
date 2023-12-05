<?php

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Transaction::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findTransactionsWithUsersByCard($cardNumber, $itemsPerPage, $page, $receiver, $description, $sortField, $sortOrder)
    {
        $offset = ($page - 1) * $itemsPerPage;

        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.senderCard = :cardNumber OR t.receiverCard = :cardNumber')
            ->setParameter('cardNumber', $cardNumber)
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage);

        if ($receiver !== null) {
            $queryBuilder->andWhere('t.receiverCard LIKE :receiver')
                ->setParameter('receiver', '%' . $receiver . '%');
        }

        if ($description !== null) {
            $queryBuilder->andWhere('t.description LIKE :description')
                ->setParameter('description', '%' . $description . '%');
        }
        if ($sortField !== null && $sortOrder !== null) {
            $queryBuilder->orderBy('t.' . $sortField, $sortOrder);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


    public function findTransactionsCount($cardNumber, $receiver, $description)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.senderCard = :cardNumber OR t.receiverCard = :cardNumber')
            ->setParameter('cardNumber', $cardNumber);

        if ($receiver !== null) {
            $queryBuilder->andWhere('t.receiverCard LIKE :receiver')
                ->setParameter('receiver', '%' . $receiver . '%');
        }

        if ($description !== null) {
            $queryBuilder->andWhere('t.description LIKE :description')
                ->setParameter('description', '%' . $description . '%');
        }

        $countQuery = $queryBuilder->getQuery();

        return $countQuery->getSingleScalarResult();
    }

    public function findStatOfDate($cardNumber)
    {
        return $this->createQueryBuilder('t')
            ->select("DATE_DIFF(t.date, '1970-01-01') AS date, SUM(t.summa) AS summa")
            ->where('t.senderCard = :cardNumber')
            ->setParameter('cardNumber', $cardNumber)
            ->groupBy('date')
            ->getQuery()
            ->getResult();
    }
}
