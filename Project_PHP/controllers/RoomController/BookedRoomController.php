<?php

$repository = new BookedRoomRepository($conn);
$service = new BookedRoomService($repository);

// Nhận action từ request (ví dụ: ?action=list, ?action=add, ...)
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'list':
        // Lấy tất cả booking
        $bookedRooms = $service->findAll();
        include 'bookedroom_list_view.php'; // view hiển thị danh sách
        break;

    case 'view':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $bookedRoom = $service->findById($id);
        include 'bookedroom_detail_view.php'; // view chi tiết từng booking
        break;

    case 'add':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $checkIn = $_POST['check_in_date'];
        $checkOut = $_POST['check_out_date'];
        $today = date('Y-m-d'); // Lấy ngày hôm nay (định dạng yyyy-mm-dd)

        // Kiểm tra: check-in phải là hôm nay và check-out phải sau hôm nay
        if ($checkIn !== $today) {
            die("❌ Ngày nhận phòng phải là hôm nay: $today");
        }
        if ($checkOut <= $today) {
            die("❌ Ngày trả phòng phải sau hôm nay.");
        }

        // Nếu hợp lệ thì tạo đối tượng BookedRoom
        $bookedRoom = new BookedRoom(
            null,
            $_POST['guest_name'],
            $_POST['guest_phone'],
            $checkIn,
            $checkOut,
            $_POST['user_id'],
            $_POST['homestay_id']
        );
        $service->save($bookedRoom);
        header('Location: BookedRoomController.php?action=list');
        exit;
    } else {
        // Hiện form thêm mới
        include 'bookedroom_add_view.php';
    }
    break;


    case 'edit':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $bookedRoom = $service->findById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookedRoom->setGuestName($_POST['guest_name']);
            $bookedRoom->setGuestPhone($_POST['guest_phone']);
            $bookedRoom->setCheckIn($_POST['check_in_date']);
            $bookedRoom->setCheckOut($_POST['check_out_date']);
            $bookedRoom->setUserId($_POST['user_id']);
            $bookedRoom->setHomeStayId($_POST['homestay_id']);
            $service->update($bookedRoom);
            header('Location: BookedRoomController.php?action=list');
            exit;
        } else {
            // Hiện form sửa
            include 'bookedroom_edit_view.php';
        }
        break;

    case 'delete':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $service->delete($id);
        header('Location: BookedRoomController.php?action=list');
        exit;
        break;

    case 'user_bookings':
        $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $bookedRooms = $service->findByUserId($userId);
        include 'bookedroom_list_view.php';
        break;

    default:
        // Mặc định về danh sách
        $bookedRooms = $service->findAll();
        include 'bookedroom_list_view.php';
        break;
}
?>