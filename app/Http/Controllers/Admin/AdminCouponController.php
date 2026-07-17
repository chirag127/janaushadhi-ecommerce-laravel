<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use App\Repositories\Contracts\CouponRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCouponController extends Controller
{
    public function __construct(
        protected CouponRepositoryInterface $coupons,
    ) {}

    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => $this->coupons->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $this->coupons->create($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    public function update(StoreCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $this->coupons->update($coupon, $request->validated());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $this->coupons->delete($coupon);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }
}
