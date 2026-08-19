<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Management</div>
                <h2 class="page-title">Add Food Item</h2>
                <p class="page-subtitle">Create a new menu item with pricing, inventory, and optional imagery.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <section class="app-surface p-6 sm:p-8 lg:p-10">
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-slate-900">Add a new food item</h3>
                <p class="mt-2 text-sm text-slate-500">Enter item details, pricing, stock, and optional image so staff can sell it immediately.</p>
            </div>

            <form method="POST" action="{{ route('food_items.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid gap-6">
                    <div>
                        <label class="app-label">Name</label>
                        <input name="name" type="text" value="{{ old('name') }}" class="app-input" required>
                        @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Category</label>
                        <input name="category" type="text" value="{{ old('category') }}" class="app-input" required>
                        @error('category')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Snack type</label>
                        <input name="snack_type" type="text" value="{{ old('snack_type') }}" class="app-input">
                        @error('snack_type')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="app-label">Description</label>
                        <textarea name="description" rows="4" class="app-textarea">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label">Price</label>
                            <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" class="app-input" required>
                            @error('price')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="app-label">Cost</label>
                            <input name="cost" type="number" step="0.01" min="0" value="{{ old('cost') }}" class="app-input" required>
                            @error('cost')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="app-label">Quantity</label>
                            <input name="quantity" type="number" min="0" value="{{ old('quantity', 0) }}" class="app-input" required>
                            @error('quantity')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="app-label">Image URL</label>
                            <input name="image_url" type="url" value="{{ old('image_url') }}" class="app-input">
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
                    <button type="submit" class="app-btn-primary">Save item</button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
