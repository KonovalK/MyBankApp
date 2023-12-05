<?php

namespace App\Extensions\User;

use App\Entity\Card;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class UserCardsExtension extends AbstractUserAccessExtension
{

    /**
     * @return array
     */
    public function getAffectedMethods(): array
    {
        return [
            self::GET
        ];
    }

    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return Card::class;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return void
     */
    public function buildQuery(QueryBuilder $queryBuilder): void
    {
        $rootAlias = $queryBuilder->getRootAliases()[self::FIRST_ELEMENT_ARRAY];

        /** @var User $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();
        $binaryId = $currentUser->getId()->toBinary();

        $queryBuilder
            ->andWhere($rootAlias.'.user = :user')
            ->setParameter('user', $binaryId);
    }


}