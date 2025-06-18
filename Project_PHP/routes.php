<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/models/User.php';

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

include_once(__DIR__ . '/controllers/ManagerController/ManagerUserController.php');
include_once(__DIR__ . '/controllers/ManagerController/ManagerServiceController.php');
include_once(__DIR__ . '/controllers/ManagerController/ManagerHomestayController.php');
include_once(__DIR__ . '/controllers/ManagerController/ManagerBookedRoomController.php');
include_once(__DIR__ . '/controllers/ManagerController/ManagerBookedServiceController.php');

$conn = Database::getConnection();

switch (strtolower($controller)) {
    case 'manageruser':
        $ctrl = new ManagerUserController($conn);
        switch ($action) {
            case 'editUser':
                $ctrl->editUser($_GET['id'] ?? null);
                break;
            case 'save':
                $ctrl->save();
                break;
            case 'updateUser':
                $ctrl->updateUser();
                break;
            case 'deleteUser':
                $ctrl->deleteUser($_GET['id'] ?? null);
                break;
            case 'searchUser':
                $ctrl->searchUser();
                break;
            default:
                $ctrl->showManagerUserPage();
                break;
        }
        break;

    case 'managerservice':
        $ctrl = new ManagerServiceController($conn);
        switch ($action) {
            case 'edit':
                $ctrl->edit($_GET['id'] ?? null);
                break;
            case 'save':
                $ctrl->save();
                break;
            case 'update':
                $ctrl->update();
                break;
            case 'delete':
                $ctrl->delete($_GET['id'] ?? null);
                break;
            case 'search':
                $ctrl->search();
                break;
            default:
                $ctrl->showManagerServicePage();
                break;
        }
        break;

    case 'managerhomestay':
        $ctrl = new ManagerHomestayController($conn);
        switch ($action) {
            case 'editHomeStay':
                $ctrl->editHomeStay($_GET['id'] ?? null);
                break;
            case 'save':
                $ctrl->save();
                break;
            case 'updateHomeStay':
                $ctrl->updateHomeStay();
                break;
            case 'deleteHomeStay':
                $ctrl->deleteHomeStay($_GET['id'] ?? null);
                break;
            default:
                $ctrl->showManagerHomestayPage();
                break;
        }
        break;

    case 'managerbookedroom':
        $ctrl = new ManagerBookedRoomController($conn);
        switch ($action) {
            case 'editBookedRoom':
                $ctrl->editBookedRoom($_GET['id'] ?? null);
                break;
            case 'editBookedRoom2':
                $ctrl->editBookedRoom2($_GET['phone'] ?? null);
                break;
            case 'save':
                $ctrl->save();
                break;
            case 'updateBookedRoom':
                $ctrl->updateBookedRoom();
                break;
            case 'deleteBookedRoom':
                $ctrl->deleteBookedRoom($_GET['id'] ?? null);
                break;
            case 'searchBookedRoom':
                $ctrl->searchBookedRoom();
                break;
            default:
                $ctrl->showManagerBookedRoomPage();
                break;
        }
        break;

    case 'managerbookedservice':
        $ctrl = new ManagerBookedServiceController($conn);
        switch ($action) {
            case 'editBookedService':
                $ctrl->editBookedService($_GET['id'] ?? null);
                break;
            case 'save':
                $ctrl->save();
                break;
            case 'updateBookedService':
                $ctrl->updateBookedService();
                break;
            case 'deleteBookedService':
                $ctrl->deleteBookedService($_GET['id'] ?? null);
                break;
            case 'searchBookedService':
                $ctrl->searchBookedService();
                break;
            default:
                $ctrl->showManagerBookedServicePage();
                break;
        }
        break;

    case 'home':
        include 'views/home.php';
        break;

    default:
        echo "\nKhông tìm thấy controller: $controller";
        break;
}
