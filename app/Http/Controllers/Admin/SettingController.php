<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $definitions = Setting::groupedDefinitions();
        $values = [];

        foreach (array_keys(Setting::definitions()) as $key) {
            $values[$key] = Setting::getSetting($key);
        }

        return view('admin.settings.index', compact('definitions', 'values'));
    }

    public function update(Request $request, ActivityLogService $logger)
    {
        $rules = [];

        foreach (Setting::definitions() as $key => $definition) {
            $rules[$key] = $definition['rules'] ?? ['nullable'];
        }

        $validated = $request->validate($rules);

        Setting::setMany($validated);
        $logger->log('setting', 'update', null, ['keys' => array_keys(Setting::definitions())]);

        return back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
