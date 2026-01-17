<?php

namespace App\Services;

use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class WalletService
{
    /**
     * List all wallets for the authenticated user.
     *
     * @return Collection<int, Wallet>
     */
    public function index(): Collection
    {
        return Wallet::where('user_id', Auth::id())->get();
    }

    /**
     * Get a specific wallet.
     */
    public function show(Wallet $wallet): Wallet
    {
        return $wallet;
    }

    /**
     * Create a new wallet.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): Wallet
    {
        return Wallet::create([
            'user_id' => Auth::id(),
            'type' => $data['type'],
            'balance' => $data['balance'] ?? 0,
        ]);
    }

    /**
     * Update an existing wallet.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(array $data, Wallet $wallet): Wallet
    {
        $wallet->update($data);

        return $wallet->fresh();
    }

    /**
     * Delete a wallet.
     */
    public function destroy(Wallet $wallet): bool
    {
        return $wallet->delete();
    }
}
