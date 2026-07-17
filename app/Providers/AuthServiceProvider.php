<?php

namespace App\Providers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Policies\CouponPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        Review::class => ReviewPolicy::class,
        Coupon::class => CouponPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Blanket admin gate for the admin portal / RBAC.
        Gate::define('access-admin', fn (User $user) => $user->isAdmin());
    }
}
