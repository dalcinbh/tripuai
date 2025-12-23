<?php

namespace App\Services;

use App\Mail\TravelRequestCreated;
use App\Mail\TravelRequestStatusChanged;
use App\Models\TravelRequest;
use App\Models\User;
use App\Repositories\TravelRequestRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class TravelRequestService
{
    protected $repository;

    public function __construct(TravelRequestRepository $repository)
    {
        $this->repository = $repository;
    }

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
        return $this->repository->list($user, $filters, $perPage);
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
        $travelRequest = TravelRequest::create([
            'user_id'        => $user->id,
            'requester_name' => $data['requester_name'],
            'destination'    => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date'    => $data['return_date'],
            'status'         => TravelRequest::STATUS_SOLICITADO,
        ]);

        // Side effect: Send email
        Mail::to($user->email)->send(new TravelRequestCreated($travelRequest));

        return $travelRequest;
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

        // Side effect: Send email
        Mail::to($travelRequest->user->email)->send(new TravelRequestStatusChanged($travelRequest));

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

        // Side effect: Send email
        Mail::to($travelRequest->user->email)->send(new TravelRequestStatusChanged($travelRequest));

        return $travelRequest;
    }
}
