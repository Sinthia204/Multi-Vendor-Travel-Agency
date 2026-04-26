<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('getSetting')) {
	function getSetting(string $key, $default = null)
	{
		if (!Schema::hasTable('settings')) {
			return $default;
		}

		$settings = Cache::remember('settings.cache', 3600, function () {
			return Setting::query()->pluck('value', 'key')->toArray();
		});

		return array_key_exists($key, $settings) ? $settings[$key] : $default;
	}
}
