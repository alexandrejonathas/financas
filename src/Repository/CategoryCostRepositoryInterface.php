<?php

declare(strict_types=1);

namespace MMoney\Repository;


interface CategoryCostRepositoryInterface extends RepositoryInterface
{
    function sumByPeriod(string $dateStart, string $dateEnd, int $userId): array;
}