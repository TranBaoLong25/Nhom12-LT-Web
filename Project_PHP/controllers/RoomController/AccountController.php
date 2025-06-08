<?php
include_once('services/UserService.php');

class AccountController extends BaseController{
    private $conn;

    public function __construct($conn){
        session_start();
        $this->folder = 'account';
        $this->conn = $conn;
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $userRepo = new UserRepository($this->conn);
            $user = $userRepo->login($username, $password);
            if($user){
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'role' => $user->getRole()
                ];
                header('Location: /index.php'); 
                exit();
            }
            else{
                echo"Đăng nhập thất bại.";
            }
        }
    }
    public function register(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'];
            $role = $_POST['role'];
            if($password !== $confirmPassword){
                echo "Mật khẩu xác nhận không hợp lệ!";
                return;
            }
            $userRepo = new UserRepository($this->conn);
            $success = $userRepo->register($username, $password, $role);
            if($success){
                echo('Đăng ký thành công');
                header('Location: /login.php');
                exit();
            }
            else{
                echo "Tên đăng nhập đã tồn tại hoặc có lỗi xảy ra.";
            }
        }
    }
    public function logout(){
        session_destroy();
        header('Location: /login.php');
        exit();
    }
    public function save(){}
    public function deleteUser($id){
        
        $userRepo = new UserRepository($this->conn);
        $userRepo->deleteUser($id);
        if($_SESSION['user']->getId() == $id){
            session_destroy();
            header('Location: /login.php');
        }
        else{
            header('Location: /admin/managerUser.php');
        }
        exit();
    }
    public function updateUser(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'] ?? null;
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            if($id === null){
                echo "id khong duoc trong";
                return;
            }
            $updateUser = new User($id, $username, $password, $role);
            $userRepo = new UserRepository($this->conn);
            try{
                $userRepo->updateUser($id, $updateUser);
                header('Location: /admin/managerUser.php');
                exit();
            }catch(Exception $e){
                echo "Loi: ". $e->getMessage();
            }
        }else{
            echo "khong hop le";
        }

    }
    public function findById(){}
    public function findByUser(){}
    public function getAllUsers(){}
    public function changePassword(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_SESSION['user']->getUsername();
            $password = $_POST['password'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            if($newPassword !== $confirmPassword){
                echo "Mat khau moi xac thuc khong hop le";
                return;
            }
            $userRepo = new UserRepository($this->conn);
            $success = $userRepo->changePassword($username, $password, $newPassword);
            echo $success?"Thanh cong":"that bai";
        }
    }
}
?>
