@props(["active"])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none transition duration-200'
            : 'inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition duration-200 hover:bg-slate-100 hover:text-slate-900 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
