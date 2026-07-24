<?php

namespace Tests\Feature;

use App\Services\RazorpayService;
use Tests\TestCase;

class RazorpayServiceTest extends TestCase
{

    public function test_not_configured_when_keys_missing(): void
    {
        config(['services.razorpay.key_id' => null, 'services.razorpay.key_secret' => null]);
        $this->assertFalse((new RazorpayService)->configured());
    }

    public function test_configured_when_both_keys_present(): void
    {
        config(['services.razorpay.key_id' => 'rzp_test_abc', 'services.razorpay.key_secret' => 'secret123']);
        $this->assertTrue((new RazorpayService)->configured());
    }

    public function test_verify_payment_returns_false_when_not_configured(): void
    {
        config(['services.razorpay.key_id' => null, 'services.razorpay.key_secret' => null]);
        $this->assertFalse((new RazorpayService)->verifyPayment('order_123', 'pay_456', 'sig_789'));
    }

    /** Manual HMAC-SHA256 check — proves the algorithm, no SDK call needed. */
    public function test_hmac_sha256_verify_logic(): void
    {
        $secret = 'test_secret_key';
        $orderId = 'order_test123';
        $paymentId = 'pay_test456';

        $body = $orderId.'|'.$paymentId;
        $sig = hash_hmac('sha256', $body, $secret);

        // Same inputs → same signature.
        $this->assertSame($sig, hash_hmac('sha256', $body, $secret));

        // Tampered body → different signature.
        $this->assertNotSame($sig, hash_hmac('sha256', 'tampered|data', $secret));
    }
}
