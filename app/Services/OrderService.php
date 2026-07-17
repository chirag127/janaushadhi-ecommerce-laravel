<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use RuntimeException;

/**
 * Post-purchase order operations: cancellation (with stock restock) and
 * admin status transitions.
 */
class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
        protected ProductRepositoryInterface $products,
    ) {}

    public function cancel(Order $order): Order
    {
        if (! $order->isCancellable()) {
            throw new RuntimeException('This order can no longer be cancelled.');
        }

        // Restock the shared catalog.
        foreach ($order->items as $item) {
            \App\Models\Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }

        return $this->orders->updateStatus($order, 'cancelled');
    }

    public function updateStatus(Order $order, string $status): Order
    {
        if (! in_array($status, Order::STATUSES, true)) {
            throw new RuntimeException('Invalid order status.');
        }

        return $this->orders->updateStatus($order, $status);
    }

    public function recentForUser(User $user, int $limit = 5)
    {
        return $this->orders->recentForUser($user->id, $limit);
    }
}
