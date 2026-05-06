<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_badge' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
            'hero_image_url' => ['nullable', 'string', 'max:255'],
            'hero_cta_text' => ['nullable', 'string', 'max:120'],
            'hero_cta_url' => ['nullable', 'string', 'max:255'],
            'destinations_badge' => ['nullable', 'string', 'max:120'],
            'destinations_title' => ['nullable', 'string', 'max:255'],
            'destinations_subtitle' => ['nullable', 'string'],
            'packages_badge' => ['nullable', 'string', 'max:120'],
            'packages_title' => ['nullable', 'string', 'max:255'],
            'packages_subtitle' => ['nullable', 'string'],
            'experiences_badge' => ['nullable', 'string', 'max:120'],
            'experiences_title' => ['nullable', 'string', 'max:255'],
            'experiences_subtitle' => ['nullable', 'string'],
            'stories_badge' => ['nullable', 'string', 'max:120'],
            'stories_title' => ['nullable', 'string', 'max:255'],
            'stories_subtitle' => ['nullable', 'string'],
        ];
    }
}
