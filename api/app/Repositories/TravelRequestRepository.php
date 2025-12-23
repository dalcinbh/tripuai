<?php

namespace App\Repositories;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TravelRequestRepository
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
}
