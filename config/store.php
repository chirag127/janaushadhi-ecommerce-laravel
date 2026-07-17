<?php

return [
    // Store-wide business configuration for the Jan Aushadhi platform.
    'currency' => env('STORE_CURRENCY', 'INR'),
    'currency_symbol' => env('STORE_CURRENCY_SYMBOL', '₹'),

    // Flat shipping fee (in currency units). Free above the threshold.
    'shipping_fee' => (float) env('STORE_SHIPPING_FEE', 40),
    'free_shipping_threshold' => (float) env('STORE_FREE_SHIPPING_THRESHOLD', 500),

    // GST percentage applied at checkout.
    'tax_rate' => (float) env('STORE_TAX_RATE', 0),

    'products_per_page' => (int) env('STORE_PRODUCTS_PER_PAGE', 24),

    'support_email' => env('STORE_SUPPORT_EMAIL', 'support@janaushadhi.example'),
    'support_phone' => env('STORE_SUPPORT_PHONE', '1800-XXX-XXXX'),
];
