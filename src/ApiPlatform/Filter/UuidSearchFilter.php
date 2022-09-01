<?php

namespace App\ApiPlatform\Filter;

use ApiPlatform\Core\Api\IdentifiersExtractorInterface;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Doctrine\UuidEncoder;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class UuidSearchFilter extends SearchFilter
{
    private UuidEncoder $uuidEncoder;

    public function __construct(
        UuidEncoder $uuidEncoder,
        ManagerRegistry $managerRegistry,
        ?RequestStack $requestStack,
        IriConverterInterface $iriConverter,
        PropertyAccessorInterface $propertyAccessor = null,
        LoggerInterface $logger = null,
        array $properties = null,
        IdentifiersExtractorInterface $identifiersExtractor = null,
        NameConverterInterface $nameConverter = null
    )
    {
        $this->uuidEncoder = $uuidEncoder;
        parent::__construct($managerRegistry, $requestStack, $iriConverter, $propertyAccessor, $logger, $properties, $identifiersExtractor, $nameConverter);
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (
            null === $value ||
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass, true)
        ) {
            return;
        }

        if ('uuid' !== $this->getDoctrineFieldType($property, $resourceClass)) {
            throw new \InvalidArgumentException(sprintf('Property %s is not of type uuid', $property));
        }

        $value = (array)$value;
        $decodedValues = [];

        foreach ($value as $uuid) {
            $decoded = $this->uuidEncoder->decode($uuid);

            $decodedValues[] = $decoded ? $decoded->toString() : 'none';
        }
        if (count($decodedValues) === 1) {
            $value = $decodedValues[0];
        } else {
            $value = $decodedValues;
        }

        parent::filterProperty($property, $value, $queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
    }
}
