<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function addItems(Order $order, array $items): void
    {
        $order->items()->createMany($items);
    }

    public function findForUser(string $orderId, string $userId): ?Order
    {
        return Order::with('items')->where('id', $orderId)->where('user_id', $userId)->first();
    }

    public function findByNumber(string $orderNumber): ?Order
    {
        return Order::with('items', 'user')->where('order_number', $orderNumber)->first();
    }

    public function paginateForUser(string $userId, int $perPage): LengthAwarePaginator
    {
        return Order::forUser($userId)->withCount('items')
            ->orderByDesc('created_at')->paginate($perPage);
    }

    public function adminPaginate(array $filters, int $perPage): LengthAwarePaginator
    {
        $query = Order::with('user')->withCount('items');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['search'])) {
            $term = trim($filters['search']);
            $query->where('order_number', 'ilike', "%{$term}%");
        }

        return $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        return $order->refresh();
    }

    public function recentForUser(string $userId, int $limit = 5): Collection
    {
        return Order::forUser($userId)->orderByDesc('created_at')->limit($limit)->get();
    }

    public function salesSummary(?string $from = null, ?string $to = null): array
    {
        $query = Order::query()->whereNotIn('status', ['cancelled']);

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $revenue = (clone $query)->sum('total');
        $count = (clone $query)->count();

        // Daily revenue series (last 14 days) for the sales report chart.
        $series = Order::query()
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($r) => [
                'day' => (string) $r->day,
                'revenue' => (float) $r->revenue,
                'orders' => (int) $r->orders,
            ])->all();

        return [
            'revenue' => (float) $revenue,
            'orders' => (int) $count,
            'average_order_value' => $count > 0 ? round($revenue / $count, 2) : 0.0,
            'series' => $series,
        ];
    }

    public function countByStatus(): array
    {
        return Order::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
    }
}
