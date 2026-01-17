<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Challenge\CreateChallengeRequest;
use App\Http\Requests\Challenge\UpdateChallengeRequest;
use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use App\Services\ChallengeService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Challenges
 *
 * APIs for managing challenges.
 */
class ChallengeController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ChallengeService $service
    ) {}

    /**
     * List all challenges
     *
     * Get all challenges.
     *
     * @apiResourceCollection App\Http\Resources\ChallengeResource
     *
     * @apiResourceModel App\Models\Challenge
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Challenge::class);

        return ChallengeResource::collection($this->service->index());
    }

    /**
     * Get a challenge
     *
     * Get a specific challenge by ID.
     *
     * @apiResource App\Http\Resources\ChallengeResource
     *
     * @apiResourceModel App\Models\Challenge
     *
     * @urlParam challenge int required The challenge ID. Example: 1
     */
    public function show(Challenge $challenge): ChallengeResource
    {
        $this->authorize('view', $challenge);

        return new ChallengeResource($this->service->show($challenge));
    }

    /**
     * Create a challenge
     *
     * Create a new challenge.
     *
     * @apiResource App\Http\Resources\ChallengeResource
     *
     * @apiResourceModel App\Models\Challenge
     *
     * @response 201 scenario="Challenge created" {"data":{...},"message":"Challenge created successfully"}
     */
    public function store(CreateChallengeRequest $request): JsonResponse
    {
        $this->authorize('create', Challenge::class);

        $challenge = $this->service->store($request->validated());

        return (new ChallengeResource($challenge))
            ->additional(['message' => 'Challenge created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update a challenge
     *
     * Update an existing challenge.
     *
     * @apiResource App\Http\Resources\ChallengeResource
     *
     * @apiResourceModel App\Models\Challenge
     *
     * @urlParam challenge int required The challenge ID. Example: 1
     */
    public function update(UpdateChallengeRequest $request, Challenge $challenge): ChallengeResource
    {
        $this->authorize('update', $challenge);

        $updatedChallenge = $this->service->update($request->validated(), $challenge);

        return (new ChallengeResource($updatedChallenge))
            ->additional(['message' => 'Challenge updated successfully']);
    }

    /**
     * Delete a challenge
     *
     * Delete a challenge by ID.
     *
     * @response 200 {"message": "Challenge deleted successfully"}
     *
     * @urlParam challenge int required The challenge ID. Example: 1
     */
    public function destroy(Challenge $challenge): JsonResponse
    {
        $this->authorize('delete', $challenge);

        $this->service->destroy($challenge);

        return response()->json([
            'message' => 'Challenge deleted successfully',
        ]);
    }
}
