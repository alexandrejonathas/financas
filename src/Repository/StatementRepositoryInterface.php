<?php

declare(strict_types=1);

namespace MMoney\Repository;


interface StatementRepositoryInterface
{
    function all(string $dateStart, string $dateEnd, int $userId): array;
}