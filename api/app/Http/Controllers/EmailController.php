<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\BroadcastEmailJob;

class EmailController extends Controller
{
    /**
     * Dispatch a broadcast email job.
     */
    public function broadcast(Request $request)
    {
        //write in storage/logs/laravel.log
        $validated = $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        // Dispatch the job to the queue
        BroadcastEmailJob::dispatch($validated['subject'], $validated['body']);

        return response()->json([
            'message' => 'Broadcast email processing started in background.',
        ], 202);
    }
}
