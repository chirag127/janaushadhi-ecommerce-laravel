<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
        protected OrderService $orderService,
    ) {}

    public function index(): View
    {
        $paginated = $this->orders->paginateForUser(auth()->id(), 10);

        return view('orders.index', [
            'orders' => $paginated,
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $order->load('items'),
        ]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorize('cancel', $order);

        try {
            $this->orderService->cancel($order);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
}
