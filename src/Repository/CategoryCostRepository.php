<?php

declare(strict_types=1);

namespace MMoney\Repository;

use Illuminate\Database\Eloquent\Collection;
use MMoney\Model\BillPay;
use MMoney\Model\BillReceive;
use MMoney\Model\CategoryCost;

class CategoryCostRepository extends DefaultRepository
{
    public function __construct()
    {
        parent::__construct(CategoryCost::class);
    }

    public function sumByPeriod(string $dateStart, string $dateEnd, int $userId): array
    {
        $categories = CategoryCost::query()
            ->selectRaw("category_costs.name, sum(value) as value")
            ->leftJoin("bill_pays", "bill_pays.category_cost_id", "=", "category_costs.id")
            ->whereBetween("date_launch", [$dateStart, $dateEnd])
            ->where("category_costs.user_id", "=", $userId)
            ->whereNotNull("bill_pays.value")
            ->groupBy("value")
            ->groupBy("category_costs.name")->get();
        return $categories->toArray();
    }

}