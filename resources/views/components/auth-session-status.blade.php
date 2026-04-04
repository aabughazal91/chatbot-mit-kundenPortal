@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success fs-6 fw-medium text-success']) }}>
        {{ $status }}
    </div>
@endif
