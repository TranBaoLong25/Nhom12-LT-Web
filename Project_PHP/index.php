<?php
session_start();

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/routes.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

function toStudlyCaps($string) {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
}

$controllerClass = toStudlyCaps($controller) . 'Controller';

$controllerFile = __DIR__ . '/controllers/ManagerController/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    $controllerFile = __DIR__ . '/controllers/' . $controllerClass . '.php';
}

if (!file_exists($controllerFile)) {
    exit("Không tìm thấy controller: $controllerClass");
}

require_once $controllerFile;

$conn = Database::getConnection();
$controllerInstance = new $controllerClass($conn);

if (!method_exists($controllerInstance, $action)) {
    exit("Không tìm thấy action: $action trong controller $controllerClass");
}

$reflection = new ReflectionMethod($controllerInstance, $action);
$params = [];

foreach ($reflection->getParameters() as $param) {
    $paramName = $param->getName();
    if (isset($_GET[$paramName])) {
        $params[] = $_GET[$paramName];
    } else {    
        $params[] = null; 
    }
}

call_user_func_array([$controllerInstance, $action], $params);
