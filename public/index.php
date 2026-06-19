<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Router;
use Core\AuthMiddleware;
use Core\Database;
use Symfony\Component\HttpFoundation\Request;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Initialize database connection
Database::getConnection();

// Initialize auth middleware
AuthMiddleware::init($_ENV['JWT_SECRET'] ?? 'your-secret-key');

// Initialize router
Router::init();

// Create request from globals
$request = Request::createFromGlobals();

// Dispatch the request
$result = Router::dispatch($request);

if (isset($result['error'])) {
    http_response_code($result['status'] ?? 404);
    echo json_encode(['error' => $result['error']]);
    exit;
}

// Call the controller
[$controllerClass, $method] = $result['controller'];
$controller = new $controllerClass();
$response = $controller->$method($request, ...$result['params']);

// Send the response
$response->send();
