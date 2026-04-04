<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-dark text-uppercase fw-semibold tracking-wider shadow-sm']) }}>
    {{ $slot }}
</button>
