<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Running migrations...\n";
    $outputCode = \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    $output = \Illuminate\Support\Facades\Artisan::output();
    echo "Artisan call result: " . $outputCode . "\n";
    echo "Artisan output:\n" . $output . "\n\n";

    $tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
    echo "Connected successfully. Tables:\n";
    foreach ($tables as $table) {
        $array = (array)$table;
        echo reset($array) . "\n";
    }
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}
