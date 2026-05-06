<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

if (!function_exists('getSetting')) {
	function getSetting(string $key, $default = null)
	{
		static $settings = [];
		static $hasLoadedSettings = false;
		static $isDatabaseUnavailable = false;

		if ($isDatabaseUnavailable) {
			return $default;
		}

		if (!$hasLoadedSettings) {
			try {
				if (!Schema::hasTable('settings')) {
					$hasLoadedSettings = true;
					$settings = [];
					return $default;
				}

				$settings = Cache::remember('settings.cache', 3600, function () {
					return Setting::query()->pluck('value', 'key')->toArray();
				});
				$hasLoadedSettings = true;
			} catch (\Throwable $exception) {
				report($exception);
				$isDatabaseUnavailable = true;
				$hasLoadedSettings = true;
				$settings = [];
			}
		}

		return array_key_exists($key, $settings) ? $settings[$key] : $default;
	}
}
