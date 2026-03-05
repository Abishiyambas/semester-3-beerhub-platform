<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MachineController extends Controller
{
    protected function client()
    {
        return Http::baseUrl(config('services.beerhub_api.base_url'))
            ->acceptJson()
            ->asJson();
    }

    public function getQueue(): array
    {
        try {
            $response = $this->client()->get('/machine/queue')->throw();
            return $response->json() ?? [];
        } catch (RequestException) {
            return [];
        }
    }

    public function removeFromQueue(array $ids): bool
    {
        try {
            // The API requests an array, and if wrapped in json, then the type is wrong
            Http::baseUrl(config('services.beerhub_api.base_url'))
                ->acceptJson()
                ->withBody(json_encode(array_values($ids)), 'application/json')
                ->delete('/machine/queue/remove')
                ->throw();

            return true;
        } catch (RequestException $e) {
            return false;
        }
    }

    public function enqueueBatch(array $data): array
    {
        try {
            $response = $this->client()
                ->post('/machine/queue', $data)
                ->throw();

            return $response->json() ?? [];
        } catch (RequestException $e) {
            throw $e;
        }
    }

    public function getStatus(): ?array
    {
        try {
            $response = $this->client()
                ->get('/machine/status')
                ->throw();

            return $response->json();
        } catch (RequestException) {
            return null;
        }
    }

    public function getHeaderStatusLabel(): string
    {
        $status = $this->getStatus();

        if (! $status) {
            return 'Disconnected';
        }

        $state = $status['state'] ?? null;

        return match ($state) {
            0 => 'Deactivated',
            1 => 'Clearing',
            2 => 'Stopped',
            3 => 'Starting',
            4 => 'Idle',
            5 => 'Suspended',
            6 => 'Execute',
            7 => 'Stopping',
            8 => 'Aborting',
            9 => 'Aborted',
            10 => 'Holding',
            11 => 'Held',
            15 => 'Resetting',
            16 => 'Completing',
            17 => 'Complete',
            18 => 'Deactivating',
            19 => 'Activating',
            default => 'Unknown',
        };
    }

    public function getMaintenanceInventory(): ?array
    {
        try {
            $response = $this->client()
                ->get('/machine/maintenance-inventory')
                ->throw();

            return $response->json();
        } catch (RequestException) {
            return null;
        }
    }

    // public function index()
    // {
    //     $queue = $this->getQueue();
    //     $status = $this->getStatus();
    //     $maintenance = $this->getMaintenanceInventory();
    //
    //     return view('machine.index', [
    //         'queue' => $queue,
    //         'status' => $status,
    //         'maintenance' => $maintenance,
    //     ]);
    // }
}
