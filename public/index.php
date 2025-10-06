<?php

use Dotenv\Dotenv;
use Application\KernelFactory;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Disable error reporting in production.
if ($_ENV['ENVIRONMENT'] ?? 'prod' === 'prod') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

// Create application kernel.
$kernel = KernelFactory::createHttpKernel();

// Create the request from globals.
$request = ServerRequest::fromGlobals();

// Get the response from the application kernel.
$response = $kernel->handle($request);

// Output the response.
send($response);
