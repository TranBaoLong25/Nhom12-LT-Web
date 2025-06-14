<?php
include_once(__DIR__ . '/../../services/UserService.php');
include_once(__DIR__ . '/../../repositories/UserRepository.php');
include_once(__DIR__ . '/../../models/User.php');

class ManagerUserController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Trang chính hiển thị danh sách người dùng
    public function showManagerUserPage() {
        $userSer = new UserService(new UserRepository($this->conn));
        $users = $userSer->getAllUsers();
        $newUser = new User(null, '', '', '');
        include(__DIR__ . '/../../views/admin/managerUser.php');

    }
    public function editUser($id) {
    $userSer = new UserService(new UserRepository($this->conn));
    $user = $userSer->findById($id);

    if ($user) {
        $editUser = $user;
        $users = $userSer->getAllUsers();
        include(__DIR__ . '/../../views/admin/managerUser.php'); 
    } else {
        echo "Không tìm thấy người dùng.";
    }
}

    // Thêm người dùng mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role  = $_POST['role'];

            $user = new User(null, $username, $password, $role);
            $userSer = new UserService(new UserRepository($this->conn));
            $userSer->save($user);

            header('Location: /index.php?controller=manageruser');
            exit();
        }
    }

    // Xóa người dùng
    public function deleteUser($id) {
        $userSer = new UserService(new UserRepository($this->conn));
        $userSer->deleteUser($id);

        if ($_SESSION['user']['id'] == $id) {
            session_destroy();
            header('Location: /login.php');
        } else {
            header('Location: /index.php?controller=manageruser');
        }
        exit();
    }

    // Sửa người dùng
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            if ($id === null) {
                echo "ID không được để trống";
                return;
            }

            $updateUser = new User($id, $username, $password, $role);
            $userSer = new UserService(new UserRepository($this->conn));

            try {
                $userSer->updateUser($id, $updateUser);
                header('Location: /index.php?controller=manageruser');
                exit();
            } catch(Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ";
        }
    }

    // Tìm kiếm người dùng
    public function searchUser() {
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $userSer = new UserService(new UserRepository($this->conn));
            try {
                $user = $userSer->findByUsername($keyword);
                $users = $user ? [$user] : [];
            } catch(Exception $e) {
                $users = [];
            }

            $newUser = new User(null, '', '', '');
            include(__DIR__ . '/../../views/admin/managerUser.php');
        } else {
            echo "Thiếu từ khóa tìm kiếm.";
        }
    }

    public function findById($id) {
        $userSer = new UserService(new UserRepository($this->conn));
        return $userSer->findById($id);
    }

    public function findByUsername($username) {
        $userSer = new UserService(new UserRepository($this->conn));
        return $userSer->findByUsername($username);
    }
}
?>
