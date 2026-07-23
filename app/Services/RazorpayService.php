<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

/**
 * Thin wrapper over the Razorpay PHP SDK for the "online" checkout path.
 * Keys come from config/services.php (RAZORPAY_* env). Test mode by default —
 * use rzp_test_* keys. Returns null when unconfigured so checkout can fall
 * back to COD, mirroring the sibling astro/nextjs storefronts.
 */
class RazorpayService
{
    public function configured(): bool
    {
        return ! empty(config('services.razorpay.key_id'))
            && ! empty(config('services.razorpay.key_secret'));
    }

    public function keyId(): ?string
    {
        return config('services.razorpay.key_id');
    }

    private function api(): Api
    {
        return new Api(
            config('services.razorpay.key_id'),
            config('services.razorpay.key_secret'),
        );
    }

    /** Create a Razorpay order for `$amountInr` (rupees). Returns [id, amount] or null. */
    public function createOrder(float $amountInr, string $receipt, array $notes = []): ?array
    {
        if (! $this->configured()) {
            return null;
        }
        $order = $this->api()->order->create([
            'amount' => (int) round($amountInr * 100), // paise
            'currency' => 'INR',
            'receipt' => substr($receipt, 0, 40),
            'notes' => $notes,
        ]);

        return ['id' => $order['id'], 'amount' => $order['amount']];
    }

    /** Verify the checkout callback signature. Throws on tamper; true on valid. */
    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        if (! $this->configured()) {
            return false;
        }
        try {
            $this->api()->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }
}
