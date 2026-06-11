<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $definitions = Setting::groupedDefinitions();
        $values = [];
        $examLink = auth()->user()->exam_token
            ? route('owner.exam-link', auth()->user()->exam_token)
            : null;

        foreach (array_keys(Setting::definitions()) as $key) {
            $values[$key] = Setting::getSetting($key);
        }

        return view('admin.settings.index', compact('definitions', 'values', 'examLink'));
    }

    public function update(Request $request, ActivityLogService $logger)
    {
        $rules = [];

        foreach (Setting::definitions() as $key => $definition) {
            $rules[$key] = $definition['rules'] ?? ['nullable'];
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('logo_path')) {
            $oldLogoPath = Setting::getSetting('logo_path');
            $validated['logo_path'] = $request->file('logo_path')->store('settings/logo', 'public');

            if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        }

        Setting::setMany($validated);
        $logger->log('setting', 'update', null, ['keys' => array_keys(Setting::definitions())]);

        return back()->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}
