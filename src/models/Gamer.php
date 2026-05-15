<?php

namespace Models;

use PDO;
use PDOException;

class Gamer {
    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $created_at;
    public $role;
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    // Загрузка по ID
    public function load($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM gamers WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->username = $data['username'];
                $this->email = $data['email'];
                $this->password_hash = $data['password_hash'];
                $this->created_at = $data['created_at'];
                $this->role = $data['role'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return false;
        }
    }
    
    // Загрузка по логину (для входа)
    public function loadByUsername($username) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM gamers WHERE username = ?");
            $stmt->execute([$username]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->username = $data['username'];
                $this->email = $data['email'];
                $this->password_hash = $data['password_hash'];
                $this->created_at = $data['created_at'];
                $this->role = $data['role'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return false;
        }
    }
    
    // Сохранение (регистрация или обновление)
    public function save() {
        try {
            if ($this->id) {
                $stmt = $this->db->prepare("UPDATE gamers SET username = ?, email = ?, password_hash = ?, role = ? WHERE id = ?");
                return $stmt->execute([$this->username, $this->email, $this->password_hash, $this->role, $this->id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO gamers (username, email, password_hash, role, created_at) VALUES (?, ?, ?, ?, NOW())");
                $result = $stmt->execute([$this->username, $this->email, $this->password_hash, $this->role]);
                if ($result) {
                    $this->id = $this->db->lastInsertId();
                }
                return $result;
            }
        } catch (PDOException $e) {
            error_log("Ошибка сохранения: " . $e->getMessage());
            return false;
        }
    }
    
    // Проверка пароля
    public function verifyPassword($password) {
        return password_verify($password, $this->password_hash);
    }
    
    // Установка пароля с хэшированием
    public function setPassword($password) {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
    
    // Получение всех пользователей (для админа)
    public static function getAll(PDO $db) {
        try {
            $stmt = $db->query("SELECT * FROM gamers ORDER BY id DESC");
            $rows = $stmt->fetchAll();
            $gamers = [];
            foreach ($rows as $row) {
                $gamer = new self($db);
                $gamer->id = $row['id'];
                $gamer->username = $row['username'];
                $gamer->email = $row['email'];
                $gamer->password_hash = $row['password_hash'];
                $gamer->created_at = $row['created_at'];
                $gamer->role = $row['role'];
                $gamers[] = $gamer;
            }
            return $gamers;
        } catch (PDOException $e) {
            error_log("Ошибка получения списка: " . $e->getMessage());
            return [];
        }
    }
}
?>