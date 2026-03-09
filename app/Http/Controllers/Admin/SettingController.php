<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'expiry_warning_days' => Setting::getValue('expiry_warning_days', 30),
            'global_low_stock_threshold' => Setting::getValue('global_low_stock_threshold', 10),
            'currency_symbol' => Setting::getValue('currency_symbol', '৳'),
            'company_name' => Setting::getValue('company_name', 'NotesShare Online Pharmacy'),
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
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'System settings updated successfully.');
    }
}
