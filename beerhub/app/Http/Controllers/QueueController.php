<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueueDeleteRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class QueueController extends Controller
{
    protected function client()
    {
        return Http::baseUrl(config('services.beerhub_api.base_url'))
            ->acceptJson()
            ->asJson();
    }

    public function fetchQueue(): array
    {
        try {
            $response = $this->client()
                ->get('/machine/queue')
                ->throw();

            return $response->json() ?? [];
        } catch (RequestException $e) {
            report($e);

            return [];
        }
    }

    public function enqueueToApi(array $payload): void
    {
        $this->client()
            ->post('/machine/queue', $payload)
            ->throw();
    }

    public function removeFromQueue(array $ids): bool
    {
        try {
            $this->client()
                ->delete('/machine/queue/remove', array_values($ids))
                ->throw();

            return true;
        } catch (RequestException $e) {
            return false;
        }
    }

    public function destroySelected(QueueDeleteRequest $request)
    {
        $ids = $request->validated()['queue_ids'];
        error_log('ey');
        $success = $this->removeFromQueue($ids);

        return redirect()
            ->route('dashboard.index')
            ->with('status', $success ? 'Selected queue items deleted.' : 'Failed to delete queue items.');
    }
}
