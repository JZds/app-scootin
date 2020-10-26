<?php

namespace App\Repository;

use App\API\Entity\Filter\Filter;
use App\API\Entity\Filter\ScooterFilter;
use App\Entity\Scooter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class ScooterRepository extends ServiceEntityRepository implements ScooterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scooter::class);
    }

    /**
     * @param ScooterFilter $scooterFilter
     * @return Scooter[]
     */
    public function findByScootersFilter(ScooterFilter $scooterFilter): array
    {
        return $this->createQueryByFilter('s', $scooterFilter)->getQuery()->getResult();
    }

    public function findCountByScootersFilter(ScooterFilter $scooterFilter): int
    {
        return $this->createQueryByFilter('s', $scooterFilter)
            ->select('COUNT(s)')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR) ?? 0
        ;
    }

    private function createQueryByFilter(string $alias, ScooterFilter $scooterFilter): QueryBuilder
    {
        $query = $this->addFilterQuery($this->createQueryBuilder($alias), $scooterFilter);
        if (count($scooterFilter->getPoints()) === 2) {
            $sql = sprintf(
                "SELECT uuid FROM scooters WHERE MBRContains(ST_GeomFromText('LINESTRING(%s,%s)'), location)",
                str_replace(',', ' ', $scooterFilter->getPoints()[0]),
                str_replace(',', ' ', $scooterFilter->getPoints()[1])
            );
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute();
            $uuids = $stmt->fetchAllAssociative();
            if (count($uuids) > 0) {
                $uuids = array_column($uuids, 'uuid');
            }
            $query->andWhere('s.uuid IN (:uuids)')->setParameter('uuids', $uuids);
        }

        if ($scooterFilter->getStatus() !== null) {
            $query
                ->andWhere('s.status = :status')
                ->setParameter('status', $scooterFilter->getStatus())
            ;
        }

        return $query;
    }

    private function addFilterQuery(QueryBuilder $queryBuilder, Filter $filter): QueryBuilder
    {
        if ($filter->getSortBy() !== null && $filter->getOrderBy() !== null) {
            $queryBuilder->orderBy($filter->getSortBy(), $filter->getOrderBy());
        }
        if ($filter->getLimit() !== null) {
            $queryBuilder->setMaxResults($filter->getLimit());
        }
        if ($filter->getOffset() !== null) {
            $queryBuilder->setFirstResult($filter->getOffset());
        }
        return $queryBuilder;
    }
}
