<?php
// app/Http/Controllers/MaintenanceController.php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    public function toggle(Request $request)
    {
        $maintenanceFile = storage_path('framework/down');
        
        if (File::exists($maintenanceFile)) {
            Artisan::call('up');
            return back()->with('success', 'Maintenance mode disabled - App is LIVE');
        } else {
            Artisan::call('down', [
                '--render' => 'errors.503',
                '--redirect' => '/maintenance',
                '--secret' => 'bypass-'.time()
            ]);
            return back()->with('success', 'Maintenance mode enabled - App is OFFLINE');
        }
    }
}