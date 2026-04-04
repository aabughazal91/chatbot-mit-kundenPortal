<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary text-uppercase fw-semibold tracking-wider shadow-sm']) }}>
    {{ $slot }}
</button>
