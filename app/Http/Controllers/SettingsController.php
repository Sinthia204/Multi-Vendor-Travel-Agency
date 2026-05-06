<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
	public function index()
	{
		$settings = Setting::query()->pluck('value', 'key')->toArray();

		return view('admin.settings', compact('settings'));
	}

	public function update(Request $request)
	{
		$data = $request->validate([
			'site_name' => ['required', 'string', 'max:120'],
			'site_logo' => ['nullable', 'image', 'max:2048'],
			'contact_email' => ['required', 'email'],
			'contact_phone' => ['nullable', 'string', 'max:40'],
			'contact_address' => ['nullable', 'string', 'max:255'],
			'contact_map_embed' => ['nullable', 'string'],
			'payment_bkash_enabled' => ['nullable', 'boolean'],
			'payment_nagad_enabled' => ['nullable', 'boolean'],
			'payment_card_enabled' => ['nullable', 'boolean'],
			'sslcommerz_store_id' => ['nullable', 'string', 'max:120'],
			'sslcommerz_store_password' => ['nullable', 'string', 'max:120'],
			'mail_driver' => ['nullable', 'string', 'max:40'],
			'mail_host' => ['nullable', 'string', 'max:120'],
			'mail_port' => ['nullable', 'numeric', 'min:1'],
			'mail_username' => ['nullable', 'string', 'max:120'],
			'mail_password' => ['nullable', 'string', 'max:120'],
			'system_currency' => ['nullable', 'string', 'max:10'],
			'system_timezone' => ['nullable', 'string', 'max:120'],
			'maintenance_mode' => ['nullable', 'boolean'],
		]);

		$existingLogo = getSetting('site_logo');

		if ($request->hasFile('site_logo')) {
			$path = $request->file('site_logo')->store('settings', 'public');
			$data['site_logo'] = $path;
		}

		$settings = [
			'site_name' => $data['site_name'],
			'site_logo' => $data['site_logo'] ?? $existingLogo,
			'contact_email' => $data['contact_email'],
			'contact_phone' => $data['contact_phone'] ?? null,
			'contact_address' => $data['contact_address'] ?? null,
			'contact_map_embed' => $data['contact_map_embed'] ?? null,
			'payment_bkash_enabled' => $request->boolean('payment_bkash_enabled'),
			'payment_nagad_enabled' => $request->boolean('payment_nagad_enabled'),
			'payment_card_enabled' => $request->boolean('payment_card_enabled'),
			'sslcommerz_store_id' => $data['sslcommerz_store_id'] ?? null,
			'sslcommerz_store_password' => $data['sslcommerz_store_password'] ?? null,
			'mail_driver' => $data['mail_driver'] ?? 'smtp',
			'mail_host' => $data['mail_host'] ?? null,
			'mail_port' => $data['mail_port'] ?? null,
			'mail_username' => $data['mail_username'] ?? null,
			'mail_password' => $data['mail_password'] ?? null,
			'system_currency' => $data['system_currency'] ?? 'BDT',
			'system_timezone' => $data['system_timezone'] ?? config('app.timezone'),
			'maintenance_mode' => $request->boolean('maintenance_mode'),
		];

		foreach ($settings as $key => $value) {
			Setting::updateOrCreate(
				['key' => $key],
				['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
			);
		}

		Cache::forget('settings.cache');

		return redirect()
			->route('admin.settings')
			->with('success', 'Settings updated successfully.');
	}
}
