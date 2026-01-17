<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\CreateWalletRequest;
use App\Http\Requests\Wallet\UpdateWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Wallets
 *
 * APIs for managing user wallets.
 */
class WalletController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected WalletService $service
    ) {}

    /**
     * List all wallets
     *
     * Get all wallets for the authenticated user.
     *
     * @apiResourceCollection App\Http\Resources\WalletResource
     *
     * @apiResourceModel App\Models\Wallet
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Wallet::class);

        return WalletResource::collection($this->service->index());
    }

    /**
     * Get a wallet
     *
     * Get a specific wallet by ID.
     *
     * @apiResource App\Http\Resources\WalletResource
     *
     * @apiResourceModel App\Models\Wallet
     *
     * @urlParam wallet int required The wallet ID. Example: 1
     */
    public function show(Wallet $wallet): WalletResource
    {
        $this->authorize('view', $wallet);

        return new WalletResource($this->service->show($wallet));
    }

    /**
     * Create a wallet
     *
     * Create a new wallet for the authenticated user.
     *
     * @apiResource App\Http\Resources\WalletResource
     *
     * @apiResourceModel App\Models\Wallet
     *
     * @response 201 scenario="Wallet created" {"data":{"id":1,"type":"USD","balance":0,"created_at":"2026-01-17T12:00:00.000000Z","updated_at":"2026-01-17T12:00:00.000000Z"},"message":"Wallet created successfully"}
     */
    public function store(CreateWalletRequest $request): JsonResponse
    {
        $this->authorize('create', Wallet::class);

        $wallet = $this->service->store($request->validated());

        return (new WalletResource($wallet))
            ->additional(['message' => 'Wallet created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update a wallet
     *
     * Update an existing wallet.
     *
     * @apiResource App\Http\Resources\WalletResource
     *
     * @apiResourceModel App\Models\Wallet
     *
     * @urlParam wallet int required The wallet ID. Example: 1
     */
    public function update(UpdateWalletRequest $request, Wallet $wallet): WalletResource
    {
        $this->authorize('update', $wallet);

        $updatedWallet = $this->service->update($request->validated(), $wallet);

        return (new WalletResource($updatedWallet))
            ->additional(['message' => 'Wallet updated successfully']);
    }

    /**
     * Delete a wallet
     *
     * Delete a wallet by ID.
     *
     * @response 200 {"message": "Wallet deleted successfully"}
     *
     * @urlParam wallet int required The wallet ID. Example: 1
     */
    public function destroy(Wallet $wallet): JsonResponse
    {
        $this->authorize('delete', $wallet);

        $this->service->destroy($wallet);

        return response()->json([
            'message' => 'Wallet deleted successfully',
        ]);
    }
}
