@csrf
{{-- Hotel form fields shared by create and edit views. --}}
<div class="row g-3">
    <div class="col-md-6">
        {{-- Primary hotel name input. --}}
        <label class="form-label">Hotel Name</label>
        <input type="text" name="name" class="tn-form-control" value="{{ old('name', $hotel->name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        {{-- City and country for location display. --}}
        <label class="form-label">City</label>
        <input type="text" name="city" class="tn-form-control" value="{{ old('city', $hotel->city ?? '') }}"
            required>
    </div>
    <div class="col-md-3">
        {{-- Country input for location display. --}}
        <label class="form-label">Country</label>
        <input type="text" name="country" class="tn-form-control" value="{{ old('country', $hotel->country ?? '') }}"
            required>
    </div>
    <div class="col-md-6">
        {{-- Optional address for detailed listing. --}}
        <label class="form-label">Address</label>
        <input type="text" name="address" class="tn-form-control"
            value="{{ old('address', $hotel->address ?? '') }}">
    </div>
    <div class="col-md-3">
        {{-- Pricing input used in listings and bookings. --}}
        <label class="form-label">Price per Night</label>
        <input type="number" step="0.01" name="price_per_night" class="tn-form-control"
            value="{{ old('price_per_night', $hotel->price_per_night ?? '') }}" required>
    </div>
    <div class="col-md-3">
        {{-- Optional rating input for display. --}}
        <label class="form-label">Rating</label>
        <input type="number" step="0.1" min="0" max="5" name="rating" class="tn-form-control"
            value="{{ old('rating', $hotel->rating ?? '') }}">
    </div>
    <div class="col-md-4">
        {{-- Status controls whether the hotel is visible/active. --}}
        <label class="form-label">Status</label>
        <select name="status" class="tn-form-control" required>
            @foreach (['active' => 'Active', 'draft' => 'Draft', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $hotel->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8">
        {{-- Upload and preview the hotel image. --}}
        <label class="form-label">Hotel Image</label>
        <input type="file" name="image" class="tn-form-control" accept="image/*">
        @if (!empty($hotel?->image_url))
            @php
                $imagePath = $hotel->image_url;
                if ($imagePath && !str_starts_with($imagePath, 'http')) {
                    $imagePath = Storage::url($imagePath);
                }
            @endphp
            <div class="mt-2">
                <img src="{{ $imagePath }}" alt="{{ $hotel->name }}" width="120" height="80"
                    style="border-radius:12px;object-fit:cover;">
            </div>
        @endif
    </div>
    <div class="col-12">
        {{-- Long-form description for the hotel listing. --}}
        <label class="form-label">Description</label>
        <textarea name="description" class="tn-form-control" rows="4">{{ old('description', $hotel->description ?? '') }}</textarea>
    </div>
</div>
