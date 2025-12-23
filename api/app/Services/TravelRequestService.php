<?php

namespace App\Services;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TravelRequestService
{
    /**
     * List travel requests for a user with filters.
     *
     * @param User $user
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function list(User $user, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return TravelRequest::with('user:id,name,email')
            ->forUser($user)
            ->filter($filters)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Create a new travel request.
     *
     * @param User $user
     * @param array $data
     * @return TravelRequest
     */
    public function create(User $user, array $data): TravelRequest
    {
        return TravelRequest::create([
            'user_id'        => $user->id,
            'requester_name' => $data['requester_name'],
            'destination'    => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date'    => $data['return_date'],
            'status'         => TravelRequest::STATUS_SOLICITADO,
        ]);
    }

    /**
     * Approve a travel request.
     *
     * @param TravelRequest $travelRequest
     * @return TravelRequest
     */
    public function approve(TravelRequest $travelRequest): TravelRequest
    {
        $travelRequest->markAsApproved();
        return $travelRequest;
    }

    /**
     * Cancel a travel request.
     *
     * @param TravelRequest $travelRequest
     * @return TravelRequest
     */
    public function cancel(TravelRequest $travelRequest): TravelRequest
    {
        $travelRequest->markAsCancelled();
        return $travelRequest;
    }
}
