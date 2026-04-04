@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold text-light']) }}>
    {{ $value ?? $slot }}
</label>
