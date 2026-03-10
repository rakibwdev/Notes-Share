<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'expiry_warning_days' => Setting::getValue('expiry_warning_days', 30),
            'global_low_stock_threshold' => Setting::getValue('global_low_stock_threshold', 10),
            'currency_symbol' => Setting::getValue('currency_symbol', '৳'),
            'company_name' => Setting::getValue('company_name', 'NotesShare Online Pharmacy'),
            'logo_url' => Setting::getValue('logo_url'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expiry_warning_days' => 'required|integer|min:1|max:365',
            'global_low_stock_threshold' => 'required|integer|min:1',
            'currency_symbol' => 'required|string|max:5',
            'company_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::getValue('logo_url');
            if ($oldLogo) {
                $oldPath = str_replace('/storage/', '', $oldLogo);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'logo_url'], ['value' => Storage::url($path)]);
        }

        // Handle other settings
        $fields = ['expiry_warning_days', 'global_low_stock_threshold', 'currency_symbol', 'company_name'];
        foreach ($fields as $field) {
            Setting::updateOrCreate(['key' => $field], ['value' => $request->input($field)]);
        }

        return back()->with('success', 'System settings and logo updated successfully.');
    }
}
