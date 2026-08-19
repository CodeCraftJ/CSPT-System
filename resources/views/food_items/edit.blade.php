<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Management</div>
                <h2 class="page-title">Edit Food Item</h2>
                <p class="page-subtitle">Update pricing, inventory, or item details for your menu.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <section class="app-surface p-6 sm:p-8 lg:p-10">
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-slate-900">Update food item</h3>
                <p class="mt-2 text-sm text-slate-500">Adjust pricing, inventory, or item details without losing the current values.</p>
            </div>

            <form method="POST" action="{{ route('food_items.update', $foodItem) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                <div class="grid gap-6">
                    <div>
                        <label class="app-label">Name</label>
                        <input name="name" type="text" value="{{ old('name', $foodItem->name) }}" class="app-input" required>
                        @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Category</label>
                        <input name="category" type="text" value="{{ old('category', $foodItem->category) }}" class="app-input" required>
                        @error('category')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Snack type</label>
                        <input name="snack_type" type="text" value="{{ old('snack_type', $foodItem->snack_type) }}" class="app-input">
                        @error('snack_type')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Description</label>
                        <textarea name="description" rows="4" class="app-textarea">{{ old('description', $foodItem->description) }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label">Price</label>
                            <input name="price" type="number" step="0.01" min="0" value="{{ old('price', $foodItem->price) }}" class="app-input" required>
                            @error('price')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="app-label">Cost</label>
                            <input name="cost" type="number" step="0.01" min="0" value="{{ old('cost', $foodItem->cost) }}" class="app-input" required>
                            @error('cost')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label">Quantity</label>
                            <input name="quantity" type="number" min="0" value="{{ old('quantity', $foodItem->quantity) }}" class="app-input" required>
                            @error('quantity')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="app-label">Image URL</label>
                            <input name="image_url" type="url" value="{{ old('image_url', $foodItem->image_url) }}" class="app-input">
                            @error('image_url')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="app-label">Upload Image</label>
                        <input name="image" type="file" accept="image/*" class="app-file-input">
                        @error('image')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('food_items.index') }}" class="app-btn-secondary">Cancel</a>
                    <button type="submit" class="app-btn-primary">Update item</button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
