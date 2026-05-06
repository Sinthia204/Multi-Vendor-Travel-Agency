@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Package Name</label>
        <input type="text" name="name" class="tn-form-control" value="{{ old('name', $package->name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="tn-form-control" value="{{ old('category', $package->category ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="tn-form-control" min="0" value="{{ old('price', $package->price ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration</label>
        <input type="text" name="duration" class="tn-form-control" value="{{ old('duration', $package->duration ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="tn-form-control" value="{{ old('location', $package->location ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Capacity</label>
        <input type="number" name="capacity" class="tn-form-control" min="0" value="{{ old('capacity', $package->capacity ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Image URL</label>
        <input type="text" name="image_url" class="tn-form-control" value="{{ old('image_url', $package->image_url ?? '') }}" placeholder="https://...">
    </div>
    <div class="col-md-6">
        <label class="form-label">Gradient</label>
        <input type="text" name="gradient" class="tn-form-control" value="{{ old('gradient', $package->gradient ?? '') }}" placeholder="linear-gradient(...)" >
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="tn-form-control" required>
            <option value="active" @selected(old('status', $package->status ?? 'draft') === 'active')>Active</option>
            <option value="draft" @selected(old('status', $package->status ?? 'draft') === 'draft')>Draft</option>
        </select>
    </div>
</div>
