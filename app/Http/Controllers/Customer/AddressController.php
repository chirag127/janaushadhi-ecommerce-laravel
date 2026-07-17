<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        return view('customer.addresses', [
            'addresses' => auth()->user()->addresses()->orderByDesc('is_default')->get(),
        ]);
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        auth()->user()->addresses()->create($request->validated());

        return redirect()->route('addresses.index')->with('success', 'Address added successfully.');
    }

    public function update(StoreAddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $address->update($request->validated());

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted.');
    }

    public function makeDefault(Address $address): RedirectResponse
    {
        $this->authorizeAddress($address);

        $user = auth()->user();

        // Unset all other defaults first.
        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', 'Default address updated.');
    }

    protected function authorizeAddress(Address $address): void
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
