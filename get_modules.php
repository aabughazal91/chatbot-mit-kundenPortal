<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
file_put_contents(__DIR__ . '/modules_output.json', json_encode(App\Models\PriceModule::get()->toArray(), JSON_PRETTY_PRINT));
