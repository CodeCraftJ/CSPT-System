@props(["active"])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-2xl bg-slate-900 px-4 py-3 text-start text-base font-semibold text-white focus:outline-none transition duration-200'
            : 'block w-full rounded-2xl px-4 py-3 text-start text-base font-semibold text-slate-700 focus:outline-none transition duration-200 hover:bg-slate-100 hover:text-slate-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
