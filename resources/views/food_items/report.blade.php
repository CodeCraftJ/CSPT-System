<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Printable Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="page-container py-8">
        <div class="no-print mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Printable Sales Report</h1>
                <p class="text-sm text-slate-500">Use the print button or your browser print dialog.</p>
            </div>
            <button type="button" onclick="window.print()" class="app-btn-primary">Print</button>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Food items report</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th class="text-right">Cost Price</th>
                            <th class="text-right">Selling Price</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Sold</th>
                            <th class="text-right">Remaining</th>
                            <th class="text-right">Total Revenue</th>
                            <th class="text-right">Profit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($items as $item)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-900">{{ $item->product }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $item->type }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">₱{{ number_format((float) $item->cost_price, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">₱{{ number_format((float) $item->selling_price, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">{{ (int) $item->stock }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">{{ (int) $item->sold }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">{{ (int) $item->remaining }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">₱{{ number_format((float) $item->total_revenue, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">₱{{ number_format((float) $item->profit, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4">{{ $item->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-10 text-center text-sm text-slate-500">No report data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
