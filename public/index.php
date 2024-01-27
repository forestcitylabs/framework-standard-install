<?php

use Dotenv\Dotenv;
use Application\KernelFactory;
use GuzzleHttp\Psr7\ServerRequest;

use function Http\Response\send;

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables.
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Create application kernel.
$kernel = KernelFactory::createHttpKernel();

// Create the request from globals.
$request = ServerRequest::fromGlobals();

// Get the response from the application kernel.
$response = $kernel->handle($request);

// Output the response.
send($response);
