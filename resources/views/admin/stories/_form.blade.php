@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="tn-form-control" value="{{ old('title', $story->title ?? '') }}"
            required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="tn-form-control" value="{{ old('category', $story->category ?? '') }}"
            placeholder="Adventure">
    </div>
    <div class="col-md-3">
        <label class="form-label">Read Time</label>
        <input type="text" name="read_time" class="tn-form-control" value="{{ old('read_time', $story->read_time ?? '') }}"
            placeholder="5 min read">
    </div>
    <div class="col-12">
        <label class="form-label">Excerpt</label>
        <textarea name="excerpt" class="tn-form-control" rows="4" required>{{ old('excerpt', $story->excerpt ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Image Upload</label>
        <input type="file" name="image" class="tn-form-control" accept="image/*">
    </div>
    <div class="col-md-6">
        <label class="form-label">Image URL (optional)</label>
        <input type="text" name="image_url" class="tn-form-control"
            value="{{ old('image_url', $story->image_url ?? '') }}" placeholder="https://...">
    </div>
    <div class="col-md-6">
        <label class="form-label">Link URL</label>
        <input type="text" name="link_url" class="tn-form-control"
            value="{{ old('link_url', $story->link_url ?? '') }}" placeholder="https://...">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" class="tn-form-control" min="0"
            value="{{ old('sort_order', $story->sort_order ?? 0) }}">
    </div>
    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check form-switch mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                @checked(old('is_active', $story->is_active ?? true))>
            <label class="form-label">Active</label>
        </div>
    </div>
    @if (!empty($story?->image_url))
        @php
            $imagePath = $story->image_url;
            if ($imagePath && !str_starts_with($imagePath, 'http')) {
                $imagePath = Storage::url($imagePath);
            }
        @endphp
        <div class="col-12">
            <img src="{{ $imagePath }}" alt="{{ $story->title }}" width="180" height="120"
                style="border-radius:12px;object-fit:cover;">
        </div>
    @endif
</div>
