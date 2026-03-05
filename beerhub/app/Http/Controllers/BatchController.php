<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function batchLogs()
    {
        $batches = Batch::where('user_id', Auth::id())
            ->with('beerType')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard-batch-logs', [
            'batches' => $batches,
        ]);
    }
}
