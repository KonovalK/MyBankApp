<?php

namespace App\Repository;

use App\Entity\CardTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CardTemplate>
 *
 * @method CardTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardTemplate[]    findAll()
 * @method CardTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardTemplate::class);
    }

//    /**
//     * @return CardTemplate[] Returns an array of CardTemplate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CardTemplate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
