<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\Core\Persistence\Legacy\Filter\SortClauseQueryBuilder\Location;

use eZ\Publish\SPI\Persistence\Filter\Doctrine\FilteringQueryBuilder;
use eZ\Publish\SPI\Repository\Values\Filter\FilteringSortClause;
use eZ\Publish\SPI\Repository\Values\Filter\SortClauseQueryBuilder;

/**
 * @internal
 */
abstract class BaseLocationSortClauseQueryBuilder implements SortClauseQueryBuilder
{
    public function buildQuery(
        FilteringQueryBuilder $queryBuilder,
        FilteringSortClause $sortClause
    ): void {
        $sort = $this->getSortingExpression();
        $queryBuilder
            ->addSelect($this->getSortingExpression())
            ->joinAllLocations();

        /** @var \eZ\Publish\API\Repository\Values\Content\Query\SortClause $sortClause */
        $queryBuilder->addOrderBy($sort, $sortClause->direction);
    }

    abstract protected function getSortingExpression(): string;
}
