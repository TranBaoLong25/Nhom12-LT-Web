    <?php
    include_once(__DIR__ . '/../../services/IUserService.php');
    include_once(__DIR__ . '/../../repositories/IUserRepository.php');
    include_once(__DIR__ . '/../../services/UserService.php');
    include_once(__DIR__ . '/../../repositories/UserRepository.php');
    include_once(__DIR__ . '/../../models/User.php');

    class ManagerUserController {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function showManagerUserPage() {
            $userSer = new UserService(new UserRepository($this->conn));
            $users = $userSer->getAllUsers();
            $editUser = $_SESSION['editUser'] ?? null;
            include(__DIR__ . '/../../views/admin/managerUser.php'); 
        }

        public function editUser($id) { 
            $userSer = new UserService(new UserRepository($this->conn));
            $user = $userSer->findById($id);
            if ($user) {
                $_SESSION['editUser'] = $user;
                $this->showManagerUserPage();
                exit();
            } else {
                echo "Không tìm thấy người dùng.";
            }
        }

        public function save() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? '';
                $user = new User(null, $username, $password, $role);
                $userSer = new UserService(new UserRepository($this->conn));
                $userSer->save($user);
                header('Location: /index.php?controller=manageruser');
                exit();
            }
        }

        public function updateUser() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'] ?? null;
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? '';

                if (!$id) {
                    echo "ID không hợp lệ!";
                    return;
                }

                $user = new User($id, $username, $password, $role);
                $userSer = new UserService(new UserRepository($this->conn));
                try {
                    $userSer->updateUser($id, $user);
                    header('Location: /index.php?controller=manageruser');
                    exit();
                } catch (Exception $e) {
                    echo "Lỗi cập nhật: " . $e->getMessage();
                }
            }
        }

        public function deleteUser($id) {
            $userSer = new UserService(new UserRepository($this->conn));
            $userSer->deleteUser($id);
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $id) {
                session_destroy();
                header('Location: /views/login.php');
            } else {
                header('Location: /index.php?controller=manageruser');
            }
            exit();
        }

        public function searchUser() {
            $keyword = $_GET['keyword'] ?? '';
            $userSer = new UserService(new UserRepository($this->conn));
            $users = (!empty($keyword)) ? ($userSer->findByUsername($keyword) ? [$userSer->findByUsername($keyword)] : []) : $userSer->getAllUsers();
            $editUser = $_SESSION['editUser'] ?? null;
            include(__DIR__ . '/../../views/admin/managerUser.php');
            unset($_SESSION['editUser']);
        }
    }
