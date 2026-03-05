<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class CommandController extends Controller
{
    protected function client()
    {
        return Http::baseUrl(config('services.beerhub_api.base_url'))
            ->acceptJson()
            ->asJson();
    }

    /**
     *  - 0: Reset
     *  - 1: Start
     *  - 2: Stop
     *  - 3: Abort
     *  - 4: Clear
     */
    public function send(int $id)
    {
        try {
            $this->client()
                ->post("/machine/command/{$id}")
                ->throw();

            return redirect()
                ->back()
                ->with('status', 'Command sent.');
        } catch (RequestException $e) {
            $message = $e->response?->json('message') ?? 'Failed to send command.';

            return redirect()
                ->back()
                ->withErrors(['command' => $message]);
        }
    }
}
