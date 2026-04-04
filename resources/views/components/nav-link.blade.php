@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link fw-bold text-dark active'
            : 'nav-link text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
