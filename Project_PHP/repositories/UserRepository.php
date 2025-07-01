<?php
    class UserRepository implements IUserRepository{
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }
        public function save(User $user){
            try {
                $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                return $stmt->execute([
                    $user->getUsername(),
                    $hashedPassword,
                    $user->getRole()
                ]);
            } catch (PDOException $e) {
                echo "Lỗi khi lưu user: " . $e->getMessage();
                return false;
            }
        }

        public function findById($id){
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
                return new User($data['id'], $data['username'], $data['password'], $data['role']);
            }
            return null;
        }
        public function findByUsername($username){
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data) {
            return new User($data['id'], $data['username'], $data['password'], $data['role']);
            }
            return null;
        }
        public function deleteUser($id){
            $stmt = $this->conn->prepare("DELETE FROM users where id = ?");
            return $stmt->execute([$id]);
        }
        public function getAllUsers(){
            $stmt = $this->conn->query("SELECT * FROM users");
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = new User($row['id'], $row['username'], $row['password'], $row['role']);
            }
            return $users;

        }
        public function updateUser($id, $newData){ 
            $user = $this->findById($id);
            if ($user) {
                $username = $newData->getUsername();
                $password = $newData->getPassword();
                $role = $newData->getRole();
                $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
                $result = $stmt->execute([$username, $password, $role, $id]);
            if (!$result) {
                throw new Exception("Lỗi khi cập nhật user.");
            }
            } else {
                throw new Exception("User với ID này không tồn tại.");
            }
        }
        public function login($username, $password) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $storedPassword = $user['password'];
                if (password_verify($password, $storedPassword)) {
                    return new User($user['id'], $user['username'], $storedPassword, $user['role']);
                }
                if ($password === $storedPassword) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updateStmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updateStmt->execute([$hashedPassword, $user['id']]);

                    return new User($user['id'], $user['username'], $hashedPassword, $user['role']);
                }
            }

            return null; 
        }

        public function register(User $user){
            $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO users(username, password, role) VALUES (?, ?, ?)");
            return $stmt->execute([
                $user->getUsername(),
                $hashedPassword,
                $user->getRole()
            ]);
        }
        public function changePassword($username, $password, $newPassword){
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user && password_verify($password, $user['password'])){
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE username = ?");
                $stmt->execute([$hashedNewPassword, $username]);
                return true;
            }
            return false;
        }

    }
?>