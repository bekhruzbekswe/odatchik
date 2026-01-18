<?php

namespace App\Services;

use App\Models\Checkin;
use Illuminate\Support\Collection;

class CheckinService{

    /**
     * List of checkings
     * @return Collection<int , Checkin>
     */
    public function index(): Collection
    {
        return Checkin::all();
    }

    /**
     * Specific checkin
     */
    public function show(Checkin $checkin): Checkin
    {
        return $checkin;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function store(array $data): Checkin
    {
        return Checkin::create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(array $data , Checkin $checkin): Checkin
    {
        $checkin->update($data);

        return $checkin->fresh();
    }

    public function destroy(Checkin $checkin): bool
    {
        return $checkin->delete();
    }
}