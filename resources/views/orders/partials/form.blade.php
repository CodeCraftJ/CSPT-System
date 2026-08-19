@php
    $formItems = old('items');
    if (! is_array($formItems) || $formItems === []) {
        $formItems = $existingItems ?? [];
    }
    if ($formItems === []) {
        $formItems = [[
            'food_item_id' => $foodItems->first()?->id ?? '',
            'quantity' => 1,
        ]];
    }
    $formStatus = old('status', $order->status ?? 'pending');
    $foodItemsData = $foodItems->map(fn ($foodItem) => [
        'id' => $foodItem->id,
        'name' => $foodItem->name,
        'type' => $foodItem->snack_type ?: $foodItem->category,
        'price' => (float) $foodItem->price,
        'stock' => (int) $foodItem->quantity,
    ])->values();
@endphp

<section class="app-surface p-6 sm:p-8 lg:p-10">
    <form method="POST" action="{{ $action }}" class="space-y-8" data-order-form>
        @csrf
        @if(strtoupper($method) !== 'POST')
            @method($method)
        @endif

        @if($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_280px]">
            <div class="space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-slate-900">{{ $title }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $subtitle }}</p>
                    </div>
                    <button type="button" id="add-order-row" class="app-btn-secondary">Add product</button>
                </div>

                <div id="order-rows" class="space-y-4"></div>
            </div>

            <div class="space-y-4">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <label class="app-label" for="status">Order Status</label>
                    <select id="status" name="status" class="app-input mt-2 w-full">
                        <option value="pending" @selected($formStatus === 'pending')>Pending</option>
                        <option value="completed" @selected($formStatus === 'completed')>Completed</option>
                    </select>
                    <p class="mt-2 text-xs text-slate-500">Completed orders update inventory immediately.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Total order amount</p>
                    <p id="order-total" class="mt-4 text-3xl font-semibold text-slate-900">₱0.00</p>
                    <p class="mt-2 text-sm text-slate-500">Subtotal is calculated from price x quantity for each row.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Available products</p>
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $foodItems->count() }}</p>
                    <p class="mt-2 text-sm text-slate-500">Choose from the current menu and stock list.</p>
                </div>
            </div>
        </div>

        <template id="order-row-template">
            <div class="order-row rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm" data-order-row>
                <div class="grid gap-4 lg:grid-cols-[minmax(0,1.8fr)_minmax(0,1fr)_110px_130px_130px_auto]">
                    <div class="relative">
                        <label class="app-label">Product</label>
                        <input
                            type="text"
                            class="app-input mt-2 w-full"
                            data-product-search
                            placeholder="Search product name"
                            autocomplete="off"
                        />
                        <input type="hidden" data-order-field="food_item_id">
                        <div class="absolute left-0 right-0 top-[calc(100%+0.25rem)] z-20 hidden max-h-56 overflow-auto rounded-2xl border border-slate-200 bg-white shadow-lg" data-product-suggestions></div>
                    </div>

                    <div>
                        <label class="app-label">Type</label>
                        <input type="text" class="app-input mt-2 w-full bg-slate-100" data-order-output="type" readonly>
                    </div>

                    <div>
                        <label class="app-label">Unit Price</label>
                        <input type="text" class="app-input mt-2 w-full bg-slate-100" data-order-output="price" readonly>
                    </div>

                    <div>
                        <label class="app-label">Quantity</label>
                        <input type="number" min="1" value="1" class="app-input mt-2 w-full" data-order-field="quantity">
                    </div>

                    <div>
                        <label class="app-label">Subtotal</label>
                        <input type="text" class="app-input mt-2 w-full bg-slate-100" data-order-output="subtotal" readonly>
                    </div>

                    <div class="flex items-end">
                        <button type="button" class="app-btn-secondary w-full" data-order-remove>Remove</button>
                    </div>
                </div>
            </div>
        </template>

        <div class="flex flex-wrap justify-end gap-3 pt-2">
            <a href="{{ $backRoute }}" class="app-btn-secondary">Cancel</a>
            <button type="submit" class="app-btn-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rowsContainer = document.getElementById('order-rows');
        const template = document.getElementById('order-row-template');
        const totalOutput = document.getElementById('order-total');
        const addButton = document.getElementById('add-order-row');
        const currency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' });
        const productData = @json($foodItemsData);
        const initialItems = @json($formItems);

        function findProduct(id) {
            return productData.find((product) => String(product.id) === String(id));
        }

        function hideSuggestions(row) {
            const suggestions = row.querySelector('[data-product-suggestions]');
            suggestions.classList.add('hidden');
            suggestions.innerHTML = '';
        }

        function updateRow(row) {
            const hiddenId = row.querySelector('[data-order-field="food_item_id"]');
            const quantityInput = row.querySelector('[data-order-field="quantity"]');
            const typeOutput = row.querySelector('[data-order-output="type"]');
            const priceOutput = row.querySelector('[data-order-output="price"]');
            const subtotalOutput = row.querySelector('[data-order-output="subtotal"]');
            const product = findProduct(hiddenId.value);
            const quantity = Number(quantityInput.value || 0);

            if (!product) {
                typeOutput.value = '';
                priceOutput.value = '';
                subtotalOutput.value = currency.format(0);
                subtotalOutput.dataset.amount = '0';
                return;
            }

            const subtotal = product.price * quantity;

            typeOutput.value = product.type;
            priceOutput.value = currency.format(product.price);
            subtotalOutput.value = currency.format(subtotal);
            subtotalOutput.dataset.amount = String(subtotal);
        }

        function updateTotal() {
            const total = Array.from(rowsContainer.querySelectorAll('[data-order-output="subtotal"]'))
                .reduce((sum, field) => sum + Number(field.dataset.amount || 0), 0);

            totalOutput.textContent = currency.format(total);
        }

        function renumberRows() {
            Array.from(rowsContainer.querySelectorAll('[data-order-row]')).forEach((row, index) => {
                row.dataset.rowIndex = index;
                row.querySelector('[data-order-field="food_item_id"]').name = `items[${index}][food_item_id]`;
                row.querySelector('[data-order-field="quantity"]').name = `items[${index}][quantity]`;
            });
        }

        function renderSuggestions(row, query) {
            const suggestions = row.querySelector('[data-product-suggestions]');
            const normalized = query.trim().toLowerCase();
            const matches = productData
                .filter((product) => product.name.toLowerCase().includes(normalized))
                .slice(0, 8);

            if (! matches.length) {
                suggestions.innerHTML = '<div class="px-4 py-3 text-sm text-slate-500">No matching products found.</div>';
                suggestions.classList.remove('hidden');
                return;
            }

            suggestions.innerHTML = matches.map((product) => `
                <button
                    type="button"
                    class="block w-full border-b border-slate-100 px-4 py-3 text-left text-sm transition hover:bg-slate-50"
                    data-product-option
                    data-product-id="${product.id}"
                >
                    <div class="font-semibold text-slate-900">${product.name}</div>
                    <div class="text-xs text-slate-500">${product.type} · ${currency.format(product.price)} · ${product.stock} in stock</div>
                </button>
            `).join('');

            suggestions.classList.remove('hidden');
        }

        function selectProduct(row, product) {
            const hiddenId = row.querySelector('[data-order-field="food_item_id"]');
            const searchInput = row.querySelector('[data-product-search]');

            hiddenId.value = product.id;
            searchInput.value = product.name;
            hideSuggestions(row);
            updateRow(row);
            updateTotal();
        }

        function bindRow(row) {
            const searchInput = row.querySelector('[data-product-search]');
            const hiddenId = row.querySelector('[data-order-field="food_item_id"]');
            const quantityInput = row.querySelector('[data-order-field="quantity"]');
            const removeButton = row.querySelector('[data-order-remove]');
            const suggestions = row.querySelector('[data-product-suggestions]');

            searchInput.addEventListener('input', () => {
                hiddenId.value = '';
                updateRow(row);
                renderSuggestions(row, searchInput.value);
            });

            searchInput.addEventListener('focus', () => {
                renderSuggestions(row, searchInput.value);
            });

            searchInput.addEventListener('blur', () => {
                window.setTimeout(() => hideSuggestions(row), 150);
            });

            suggestions.addEventListener('mousedown', (event) => {
                const option = event.target.closest('[data-product-option]');
                if (! option) {
                    return;
                }

                event.preventDefault();
                const product = findProduct(option.dataset.productId);
                if (product) {
                    selectProduct(row, product);
                }
            });

            quantityInput.addEventListener('input', () => {
                updateRow(row);
                updateTotal();
            });

            removeButton.addEventListener('click', () => {
                row.remove();

                if (! rowsContainer.querySelector('[data-order-row]')) {
                    addRow();
                }

                renumberRows();
                refreshAllRows();
            });
        }

        function refreshAllRows() {
            Array.from(rowsContainer.querySelectorAll('[data-order-row]')).forEach((row) => updateRow(row));
            updateTotal();
        }

        function addRow(item = {}) {
            const fragment = template.content.cloneNode(true);
            const row = fragment.querySelector('[data-order-row]');
            const quantityInput = row.querySelector('[data-order-field="quantity"]');

            if (item.food_item_id) {
                const product = findProduct(item.food_item_id);
                if (product) {
                    row.querySelector('[data-order-field="food_item_id"]').value = product.id;
                    row.querySelector('[data-product-search]').value = product.name;
                }
            }

            quantityInput.value = item.quantity ?? 1;

            rowsContainer.appendChild(fragment);
            const appendedRow = rowsContainer.lastElementChild;
            bindRow(appendedRow);
            renumberRows();
            updateRow(appendedRow);
            updateTotal();
        }

        addButton.addEventListener('click', () => addRow());

        initialItems.forEach((item) => addRow(item));

        if (! initialItems.length) {
            addRow();
        }

        refreshAllRows();
    });
</script>
