<x-mail::message>
# Order Confirmed

Thank you for your order! Your order **{{ $order->order_number }}** has been confirmed.

## Order Summary

<x-mail::table>
| Product | Qty | Price |
|:--------|----:|------:|
@foreach ($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | ₹{{ number_format((float) $item->line_total, 2) }} |
@endforeach
</x-mail::table>

| | |
|-|-:|
| Subtotal | ₹{{ number_format((float) $order->subtotal, 2) }} |
@if ((float) $order->discount > 0)
| Discount | −₹{{ number_format((float) $order->discount, 2) }} |
@endif
@if ((float) $order->shipping > 0)
| Shipping | ₹{{ number_format((float) $order->shipping, 2) }} |
@else
| Shipping | Free |
@endif
| **Total** | **₹{{ number_format((float) $order->total, 2) }}** |

**Payment method:** {{ strtoupper($order->payment_method) }}

We will notify you when your order is shipped.

Thanks,
{{ config('app.name') }}
</x-mail::message>
