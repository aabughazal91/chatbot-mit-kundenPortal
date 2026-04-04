<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger text-uppercase fw-semibold tracking-wider shadow-sm']) }}>
    {{ $slot }}
</button>
