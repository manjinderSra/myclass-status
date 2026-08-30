<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/plain');

/*
|--------------------------------------------------------------------------
| Catch fatal PHP errors
|--------------------------------------------------------------------------
*/

register_shutdown_function(function () {
    $error = error_get_last();

    if ($error !== null) {
        echo "\n\n========== FATAL ERROR ==========\n";
        echo "MESSAGE: " . $error['message'] . "\n";
        echo "FILE: " . $error['file'] . "\n";
        echo "LINE: " . $error['line'] . "\n";
        echo "=================================\n";
    }
});

try {

    echo "STEP 1: PHP running\n";

    /*
    |--------------------------------------------------------------------------
    | Create writable storage in Vercel /tmp
    |--------------------------------------------------------------------------
    */

    $storagePath = '/tmp/laravel-storage';

    $directories = [
        $storagePath,
        $storagePath . '/app',
        $storagePath . '/framework',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/testing',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
    ];

    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    echo "STEP 2: /tmp storage ready\n";

    /*
    |--------------------------------------------------------------------------
    | Composer
    |--------------------------------------------------------------------------
    */

    require __DIR__ . '/../vendor/autoload.php';

    echo "STEP 3: Composer loaded\n";

    /*
    |--------------------------------------------------------------------------
    | Laravel
    |--------------------------------------------------------------------------
    */

    $app = require_once __DIR__ . '/../bootstrap/app.php';

    echo "STEP 4: Laravel application created\n";

    /*
    |--------------------------------------------------------------------------
    | Tell Laravel to use Vercel writable storage
    |--------------------------------------------------------------------------
    */

    $app->useStoragePath($storagePath);

    echo "STEP 5: Storage path changed to {$storagePath}\n";

    /*
    |--------------------------------------------------------------------------
    | Kernel
    |--------------------------------------------------------------------------
    */

    $kernel = $app->make(
        Illuminate\Contracts\Http\Kernel::class
    );

    echo "STEP 6: Kernel created\n";

    $request = Illuminate\Http\Request::capture();

    echo "STEP 7: Request captured\n";

    /*
    |--------------------------------------------------------------------------
    | Handle request
    |--------------------------------------------------------------------------
    */

    $response = $kernel->handle($request);

    echo "STEP 8: Laravel handled request\n";

    /*
    |--------------------------------------------------------------------------
    | Send actual response
    |--------------------------------------------------------------------------
    */

    header_remove('Content-Type');

    $response->send();

    $kernel->terminate($request, $response);

} catch (Throwable $e) {

    http_response_code(500);

    echo "\n\n========== LARAVEL ERROR ==========\n";
    echo "TYPE: " . get_class($e) . "\n";
    echo "MESSAGE: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
    echo "====================================\n";

    error_log($e->__toString());
}