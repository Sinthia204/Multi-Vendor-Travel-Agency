@csrf
{{-- Transport form fields used by create and edit views. --}}
<div class="row g-3">
    <div class="col-md-6">
        {{-- Basic transport identity. --}}
        <label class="form-label">Transport Name</label>
        <input type="text" name="name" class="tn-form-control" value="{{ old('name', $transport->name ?? '') }}"
            required>
    </div>
    <div class="col-md-3">
        {{-- Transport type (e.g., Bus, Boat, Train). --}}
        <label class="form-label">Type</label>
        <input type="text" name="type" class="tn-form-control" value="{{ old('type', $transport->type ?? '') }}"
            required>
    </div>
    <div class="col-md-3">
        {{-- Optional provider details. --}}
        <label class="form-label">Provider</label>
        <input type="text" name="provider" class="tn-form-control"
            value="{{ old('provider', $transport->provider ?? '') }}">
    </div>
    <div class="col-md-3">
        {{-- Pricing and capacity inputs. --}}
        <label class="form-label">Price per Trip</label>
        <input type="number" step="0.01" name="price_per_trip" class="tn-form-control"
            value="{{ old('price_per_trip', $transport->price_per_trip ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" class="tn-form-control"
            value="{{ old('capacity', $transport->capacity ?? '') }}">
    </div>
    <div class="col-md-3">
        {{-- Availability state. --}}
        <label class="form-label">Status</label>
        <select name="status" class="tn-form-control" required>
            @foreach (['active' => 'Active', 'draft' => 'Draft', 'inactive' => 'Inactive'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $transport->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        {{-- Upload and preview transport image. --}}
        <label class="form-label">Image</label>
        <input type="file" name="image" class="tn-form-control" accept="image/*">
        @if (!empty($transport?->image_url))
            @php
                $imagePath = $transport->image_url;
                if ($imagePath && !str_starts_with($imagePath, 'http')) {
                    $imagePath = Storage::url($imagePath);
                }
            @endphp
            <div class="mt-2">
                <img src="{{ $imagePath }}" alt="{{ $transport->name }}" width="120" height="80"
                    style="border-radius:12px;object-fit:cover;">
            </div>
        @endif
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="tn-form-control" rows="4">{{ old('description', $transport->description ?? '') }}</textarea>
    </div>
</div>
