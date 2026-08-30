<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: text/plain');

try {
    echo "STEP 1: PHP running\n";

    $autoload = __DIR__ . '/../vendor/autoload.php';

    if (!file_exists($autoload)) {
        throw new Exception("vendor/autoload.php DOES NOT EXIST");
    }

    echo "STEP 2: vendor/autoload.php found\n";

    require $autoload;

    echo "STEP 3: Composer loaded\n";

    $bootstrap = __DIR__ . '/../bootstrap/app.php';

    if (!file_exists($bootstrap)) {
        throw new Exception("bootstrap/app.php DOES NOT EXIST");
    }

    echo "STEP 4: bootstrap/app.php found\n";

    $app = require_once $bootstrap;

    echo "STEP 5: Laravel application created\n";

    $kernel = $app->make(
        Illuminate\Contracts\Http\Kernel::class
    );

    echo "STEP 6: Kernel created\n";

    $request = Illuminate\Http\Request::capture();

    echo "STEP 7: Request captured\n";

    $response = $kernel->handle($request);

    echo "STEP 8: Laravel handled request\n\n";

    $response->send();

    $kernel->terminate($request, $response);

} catch (Throwable $e) {

    http_response_code(500);

    echo "\n\n========== ERROR ==========\n";
    echo "MESSAGE: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
    echo "===========================\n";

    error_log($e->__toString());
}