<?php
    class UserRepository implements IUserRepository{
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function save(User $user){
            try {
                $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                return $stmt->execute([
                    $user->getUsername(),
                    $user->getPassword(),
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
            return $data;
        }
        public function findByUsername($username){
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data;
        }
        public function deleteUser($id){
            $stmt = $this->conn->prepare(["DELETE * FROM users where id = ?"]);
            return $stmt->execute([$id]);
        }
        public function getAllUsers(){
            $stmt = $this->conn->query(["SELECT * FROM users"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  
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
    }
?>