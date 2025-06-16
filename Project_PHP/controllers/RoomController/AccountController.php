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
            $userSer = new UserService(new UserRepository($this->conn));

            $user = $userSer->login($username, $password);
            if($user){
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'role' => $user->getRole()
                ];
                header('Location: /home.php'); 
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
            $userSer = new UserService(new UserRepository($this->conn));
            $user = new User(null, $username, $password, $role);
            $success = $userSer->register($user);
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
    public function save(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $role  = $_POST['role'];
            $user = new User(null, $username, $password, $role);
            $userSer = new UserService(new UserRepository($this->conn));
            $userSer->save($user);

            header('Location: /admin/managerUser.php');
            exit();
        }
    }
    public function deleteUser($id){
        
        $userSer = new UserService(new UserRepository($this->conn));

        $userSer->deleteUser($id);
        if($_SESSION['user']['id'] == $id){
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
            $userSer = new UserService(new UserRepository($this->conn));

            try{
                $userSer->updateUser($id, $updateUser);
                header('Location: /admin/managerUser.php');
                exit();
            }catch(Exception $e){
                echo "Loi: ". $e->getMessage();
            }
        }else{
            echo "khong hop le";
        }

    }
    public function findById($id){
        $userSer = new UserService(new UserRepository($this->conn));
        return $userSer->findById($id);
    }
    public function findByUsername($username){
        $userSer = new UserService(new UserRepository($this->conn));
        return $userSer->findByUsername($username);
    }
    public function getAllUsers(){
        $userSer = new UserService(new UserRepository($this->conn));
        return $userSer->getAllUsers();
    }
    public function changePassword(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $username = $_SESSION['user']['username'];
            $password = $_POST['password'] ?? '';
            $newPassword = $_POST['newPassword'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            if($newPassword !== $confirmPassword){
                echo "Mat khau moi xac thuc khong hop le";
                return;
            }
            $userSer = new UserService(new UserRepository($this->conn));

            $success = $userSer->changePassword($username, $password, $newPassword);
            echo $success?"Thanh cong":"that bai";
        }
    }
}
?>
