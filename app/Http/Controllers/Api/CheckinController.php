<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CheckinResource;
use App\Models\Checkin;
use App\Services\CheckinService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function __construct(protected CheckinService $checkin_service) {}

    /**
     * Summary of index
     *
     * @return AnonymousResourceCollection<int, CheckinResource>
     */
    public function index(): AnonymousResourceCollection
    {
        return CheckinResource::collection($this->checkin_service->index());
    }

    public function show(Checkin $checkin)
    {
        return new CheckinResource($this->checkin_service->show($checkin));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'challenge_id' => 'required|integer',
        ]);

        $data['user_id'] = Auth::id();

        $checkin = $this->checkin_service->store($data);

        return new CheckinResource($checkin);
    }

    public function update(Request $request, Checkin $checkin)
    {
        $data = $request->validate([
            'challenge_id' => 'nullable|integer',
        ]);

        $checkin = $this->checkin_service->update($data, $checkin);

        return new CheckinResource($checkin);
    }

    public function destroy(Checkin $checkin)
    {
        $this->checkin_service->destroy($checkin);

        return response()->json([
            'message' => 'Success , Checkin deleted succesfully',
        ], 201);
    }
}
