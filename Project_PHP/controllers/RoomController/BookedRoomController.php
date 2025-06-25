<?php
require_once 'BookedRoom.php';
require_once 'BookedRoomRepository.php';
require_once 'BookedRoomService.php';

$repository = new BookedRoomRepository($conn);
$service = new BookedRoomService($repository);

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $bookedRooms = $service->getAllBookedRoom(); // gọi đúng hàm getAllBookedRoom từ repo
        include 'bookedroom_list_view.php';
        break;

    case 'view':
        $id = intval($_GET['id'] ?? 0);
        $bookedRoom = $service->findById($id); // Bạn cần có hàm này trong Service và Repository
        include 'bookedroom_detail_view.php';
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $bookedRoom = new BookedRoom(
                    null,
                    $_POST['guest_name'],
                    $_POST['guest_phone'],
                    $_POST['check_in_date'],
                    $_POST['check_out_date'],
                    $_POST['user_id'],
                    $_POST['homestay_id']
                );
                $service->save($bookedRoom);
                header('Location: BookedRoomController.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                include 'bookedroom_add_view.php';
            }
        } else {
            include 'bookedroom_add_view.php';
        }
        break;

    case 'edit':
        $id = intval($_GET['id'] ?? 0);
        $bookedRoom = $service->findById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $bookedRoom->setGuestName($_POST['guest_name']);
                $bookedRoom->setGuestPhone($_POST['guest_phone']);
                $bookedRoom->setCheckIn($_POST['check_in_date']);
                $bookedRoom->setCheckOut($_POST['check_out_date']);
                $bookedRoom->setUserId($_POST['user_id']);
                $bookedRoom->setHomeStayId($_POST['homestay_id']);

                $service->updateBookedRoom($id, $bookedRoom); // đúng tên hàm
                header('Location: BookedRoomController.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                include 'bookedroom_edit_view.php';
            }
        } else {
            include 'bookedroom_edit_view.php';
        }
        break;

    case 'delete':
        $id = intval($_GET['id'] ?? 0);
        $service->deleteById($id); // phải gọi đúng deleteById trong repo
        header('Location: BookedRoomController.php?action=list');
        exit;
        break;

    case 'user_bookings':
        $userId = intval($_GET['user_id'] ?? 0);
        $bookedRooms = $service->findByUserId($userId);
        include 'bookedroom_list_view.php';
        break;

    default:
        $bookedRooms = $service->getAllBookedRoom(); // fallback
        include 'bookedroom_list_view.php';
        break;
}
?>
