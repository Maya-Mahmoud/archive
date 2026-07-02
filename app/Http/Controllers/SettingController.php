<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'organization_name' => Setting::get('organization_name', 'Archive System'),
            'max_file_size_mb' => Setting::get('max_file_size_mb', 20),
            'items_per_page' => Setting::get('items_per_page', 12),
            'contact_email' => Setting::get('contact_email', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'max_file_size_mb' => ['required', 'integer', 'min:1', 'max:1024'],
            'items_per_page' => ['required', 'integer', 'min:5', 'max:100'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ], [], [
            'organization_name' => 'organization name',
            'max_file_size_mb' => 'max file size',
            'items_per_page' => 'items per page',
            'contact_email' => 'contact email',
        ]);

        Setting::set('organization_name', $validated['organization_name']);
        Setting::set('max_file_size_mb', $validated['max_file_size_mb'], 'number');
        Setting::set('items_per_page', $validated['items_per_page'], 'number');
        Setting::set('contact_email', $validated['contact_email'] ?? '');

        return redirect()->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
