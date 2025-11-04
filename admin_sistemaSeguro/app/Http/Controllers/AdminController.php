<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function config()
    {
        $globalQuota = StorageConfig::getGlobalQuota();
        $forbiddenExtensions = StorageConfig::getForbiddenExtensions();
        return view('admin.config', compact('globalQuota', 'forbiddenExtensions'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'global_quota' => 'required|integer|min:1048576', // mínimo 1MB
            'forbidden_extensions' => 'required|string',
        ]);

        StorageConfig::updateOrCreate(
            ['config_key' => 'global_quota'],
            ['config_value' => $request->global_quota]
        );

        StorageConfig::updateOrCreate(
            ['config_key' => 'forbidden_extensions'],
            ['config_value' => json_encode(explode(',', $request->forbidden_extensions))]
        );

        return redirect()->route('admin.config')->with('success', 'Configuración actualizada.');
    }
}
