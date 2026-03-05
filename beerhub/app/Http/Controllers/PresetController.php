<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresetRequest;
use App\Http\Requests\StartPresetRequest;
use App\Models\Preset;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Auth;

class PresetController extends Controller
{
    public function list()
    {
        $user = Auth::user();

        $presets = Preset::where('user_id', $user->id)
            ->with('beerType')
            ->orderBy('name')
            ->get();

        return view('dashboard-presets', [
            'presets' => $presets,
        ]);
    }

    public function store(PresetRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();
        $data['user_id'] = $user->id;

        Preset::create($data);

        return redirect()
            ->route('dashboard.index')
            ->with('status', 'Preset saved.');
    }

    public function update(PresetRequest $request, Preset $preset)
    {
        $user = Auth::user();

        if ($preset->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validated();

        $preset->update($data);

        return redirect()
            ->route('dashboard.index', ['preset' => $preset->id])
            ->with('status', 'Preset updated.');
    }

    public function start(StartPresetRequest $request, QueueController $queueController)
    {
        $data = $request->validated();

        $batchId = random_int(10000, 99999);

        $payload = [
            'batchId' => $batchId,
            'beerType' => (int) $data['beer_type_id'],
            'quantity' => (int) $data['quantity'],
            'speed' => (int) $data['speed'],
            'userId' => (int) Auth::id(),
            'presetId' => $request->input('preset_id') !== null
                ? (int) $request->input('preset_id')
                : null,
        ];

        try {
            $queueController->enqueueToApi($payload);

            return redirect()
                ->route('dashboard.index')
                ->with('status', 'Preset started and enqueued.');
        } catch (RequestException $e) {
            $message = $e->response?->json('message') ?? 'Failed to start preset.';

            return redirect()
                ->route('dashboard.index')
                ->withErrors(['start' => $message])
                ->withInput();
        }
    }
}
