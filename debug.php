<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$modules = App\Models\PriceModule::all();
file_put_contents('debug_utf8.json', json_encode($modules, JSON_PRETTY_PRINT));
echo "Done.";
