<!-- index.php -->
<?php

require_once './config/Database.php';
require_once './models/User.php';
require_once './repository/IUserRepository.php';
require_once './repository/UserRepository.php';
require_once './services/UserService.php';

$controllerName = $_GET['controller'] ?? '';
$actionName = $_GET['action'] ?? '';

if ($controllerName === 'manageruser') {
    require_once './controllers/admin/ManagerUserController.php';
    $controller = new ManagerUserController(Database::getConnection());

    switch ($actionName) {
        case 'save':
            $controller->save();
            break;
        case 'delete':
            $controller->deleteUser($_GET['id']);
            break;
        case 'update':
            $controller->updateUser();
            break;
        case 'search':
            $controller->searchUser();
            break;
        case 'edit':
            $controller->editUser($_GET['id']);
            break;
        default:
            $controller->showManagerUserPage();
            break;
    }
} else {
    // Nếu không có controller -> chuyển về home.php
    include('home.php');
}
