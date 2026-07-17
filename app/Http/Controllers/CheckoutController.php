<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CheckoutService $checkout,
    ) {}

    public function index(): View|RedirectResponse
    {
        $user = auth()->user();
        $items = $this->cart->items($user);

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $summary = $this->cart->summary($user);
        $addresses = $user->addresses()->orderByDesc('is_default')->get();

        return view('checkout.index', [
            'items' => $items,
            'summary' => $summary,
            'addresses' => $addresses,
        ]);
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Build the shipping address array.
        if (! empty($validated['address_id'])) {
            $address = Address::where('id', $validated['address_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();
            $shippingAddress = $address->toSnapshot();
        } else {
            $shippingAddress = [
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'line1' => $validated['line1'],
                'line2' => $validated['line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
                'country' => $validated['country'] ?? 'India',
            ];
        }

        try {
            $order = $this->checkout->placeOrder($user, [
                'shipping_address' => $shippingAddress,
                'payment_method' => $validated['payment_method'],
                'coupon_code' => $validated['coupon_code'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('checkout.confirmation', $order->order_number)
            ->with('success', 'Order placed successfully!');
    }

    public function confirmation(Order $order): View
    {
        $this->authorize('view', $order);

        return view('checkout.confirmation', [
            'order' => $order->load('items'),
        ]);
    }
}
