<?php

namespace App\Http\Controllers;

use App\Models\BeerType;
use App\Models\Preset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request, QueueController $queueController, MachineController $machineController)
    {
        $user = Auth::user();

        $presets = Preset::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        $beerTypes = BeerType::orderBy('name')->get();

        $selectedPreset = null;
        if ($request->filled('preset')) {
            $selectedPreset = Preset::where('user_id', $user->id)
                ->where('id', $request->preset)
                ->first();
        }

        $queueItems = $queueController->fetchQueue();
        $headerStatus = $machineController->getHeaderStatusLabel();
        $machineData = $machineController->getMaintenanceInventory();

        return view('dashboard', [
            'presets' => $presets,
            'beerTypes' => $beerTypes,
            'selectedPreset' => $selectedPreset,
            'queueItems' => $queueItems,
            'headerStatus' => $headerStatus,
            'machineData' => $machineData,
        ]);
    }
}
