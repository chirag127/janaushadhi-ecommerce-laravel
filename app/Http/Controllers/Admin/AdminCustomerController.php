<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCustomerController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orders,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = User::where('role', User::ROLE_CUSTOMER)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at');

        $customers = $query->paginate(20)->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
            'search' => $search,
        ]);
    }

    public function show(User $user): View
    {
        $orders = $this->orders->paginateForUser($user->id, 10);

        return view('admin.customers.show', [
            'customer' => $user,
            'orders' => $orders,
        ]);
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Customer account {$status}.");
    }
}
