<?php


namespace App\Extensions;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class AbstractCurrentUserExtension
 * @package App\Extension
 * @deprecated use separate extensions for each role instead.
 */
abstract class AbstractCurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    
    public const FIRST_ELEMENT_ARRAY = 0;

    public const ADMIN_ROLES = [
        User::ROLE_ADMIN
    ];

    /**
     * @var TokenStorageInterface
     */
    protected TokenStorageInterface $tokenStorage;

    /**
     * AbstractCurrentUserExtension constructor.
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param string|null $operationName
     */
    public function applyToCollection(
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        string                      $operationName = null
    ): void
    {
        if ($this->isFiltering($operationName, $resourceClass)) {
            return;
        }

        $this->buildQuery($queryBuilder);
    }

    /**
     * @param $operationName
     * @param $resourceClass
     * @return bool
     */
    protected function isFiltering($operationName, $resourceClass): bool
    {
        $token = $this->tokenStorage->getToken();

        if (is_null($token)) {
            return true;
        }

        return !$this->apply($operationName) ||
            count(array_intersect(self::ADMIN_ROLES, $token->getRoleNames())) ||
            $resourceClass !== $this->getResourceClass();
    }

    /**
     * @param $operationName
     * @return bool
     */
    protected function apply($operationName): bool
    {
        return !is_bool(strpos($operationName, "get"));
    }

    /**
     * @return string
     */
    abstract public function getResourceClass(): string;

    /**
     * @param QueryBuilder $queryBuilder
     */
    abstract public function buildQuery(QueryBuilder $queryBuilder);

    /**
     * @param QueryBuilder $queryBuilder
     * @param QueryNameGeneratorInterface $queryNameGenerator
     * @param string $resourceClass
     * @param array $identifiers
     * @param string|null $operationName
     * @param array $context
     */
    public function applyToItem(
        QueryBuilder                $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string                      $resourceClass,
        array                       $identifiers,
        string                      $operationName = null,
        array                       $context = []
    ): void
    {
        if ($this->isFiltering($operationName, $resourceClass)) {
            return;
        }

        $this->buildQuery($queryBuilder);
    }

}