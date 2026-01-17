<?php

namespace App\Services;

use App\Models\Challenge;
use Illuminate\Support\Collection;

class ChallengeService
{
    /**
     * List all challenges.
     *
     * @return Collection<int, Challenge>
     */
    public function index(): Collection
    {
        return Challenge::all();
    }

    /**
     * Get a specific challenge.
     */
    public function show(Challenge $challenge): Challenge
    {
        return $challenge;
    }

    /**
     * Create a new challenge.
     *
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): Challenge
    {
        return Challenge::create($data);
    }

    /**
     * Update an existing challenge.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(array $data, Challenge $challenge): Challenge
    {
        $challenge->update($data);

        return $challenge->fresh();
    }

    /**
     * Delete a challenge.
     */
    public function destroy(Challenge $challenge): bool
    {
        return $challenge->delete();
    }
}
