<?php

namespace App\Observers;

use App\Mail\TravelRequestCreated;
use App\Mail\TravelRequestStatusChanged;
use App\Models\TravelRequest;
use Illuminate\Support\Facades\Mail;

class TravelRequestObserver
{

    public function created(TravelRequest $travelRequest): void
    {
        Mail::to($travelRequest->user->email)
            ->send(new TravelRequestCreated($travelRequest));
    }

    public function updated(TravelRequest $travelRequest): void
    {
        if ($travelRequest->wasChanged('status')) {
            Mail::to($travelRequest->user->email)
                ->send(new TravelRequestStatusChanged($travelRequest));
        }
    }
}
