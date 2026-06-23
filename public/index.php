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

// CORS — lista de orígenes permitidos desde .env
$allowedOrigins = array_map('trim', explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? ''));
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

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

// Call the controller with proper argument mapping
[$controllerClass, $method] = $result['controller'];
$controller = new $controllerClass();
$args = [];
foreach ((new ReflectionMethod($controllerClass, $method))->getParameters() as $param) {
    $type = $param->getType();
    if ($type instanceof ReflectionNamedType && $type->getName() === Request::class) {
        $args[] = $request;
    } elseif (array_key_exists($param->getName(), $result['params'])) {
        $value = $result['params'][$param->getName()];
        if ($type instanceof ReflectionNamedType) {
            $typeName = $type->getName();
            if ($typeName === 'int') {
                $value = (int) $value;
            } elseif ($typeName === 'float') {
                $value = (float) $value;
            }
        }
        $args[] = $value;
    } elseif ($param->isDefaultValueAvailable()) {
        $args[] = $param->getDefaultValue();
    }
}
$response = $controller->$method(...$args);

// Send the response
$response->send();
