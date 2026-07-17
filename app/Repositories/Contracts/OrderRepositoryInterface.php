<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function create(array $data): Order;

    public function addItems(Order $order, array $items): void;

    public function findForUser(string $orderId, string $userId): ?Order;

    public function findByNumber(string $orderNumber): ?Order;

    public function paginateForUser(string $userId, int $perPage): LengthAwarePaginator;

    public function adminPaginate(array $filters, int $perPage): LengthAwarePaginator;

    public function updateStatus(Order $order, string $status): Order;

    public function recentForUser(string $userId, int $limit = 5): Collection;

    /** Aggregate sales metrics for the admin dashboard/reports. */
    public function salesSummary(?string $from = null, ?string $to = null): array;

    public function countByStatus(): array;
}
