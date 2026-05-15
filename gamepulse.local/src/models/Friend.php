<?php

namespace Models;

use PDO;
use PDOException;

class Friend {
    public $id;
    public $gamer1_id;
    public $gamer2_id;
    public $status;
    public $created_at;
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function load($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM friends WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->gamer1_id = $data['gamer1_id'];
                $this->gamer2_id = $data['gamer2_id'];
                $this->status = $data['status'];
                $this->created_at = $data['created_at'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return false;
        }
    }
    
    public function save() {
        try {
            if ($this->id) {
                $stmt = $this->db->prepare("UPDATE friends SET status = ? WHERE id = ?");
                return $stmt->execute([$this->status, $this->id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO friends (gamer1_id, gamer2_id, status, created_at) VALUES (?, ?, ?, NOW())");
                $result = $stmt->execute([$this->gamer1_id, $this->gamer2_id, $this->status]);
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
    
    public function delete() {
        if (!$this->id) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM friends WHERE id = ?");
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Ошибка удаления: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getFriends(PDO $db, $gamer_id, $status = 'accepted') {
        try {
            $stmt = $db->prepare("SELECT * FROM friends WHERE (gamer1_id = ? OR gamer2_id = ?) AND status = ?");
            $stmt->execute([$gamer_id, $gamer_id, $status]);
            $rows = $stmt->fetchAll();
            $friends = [];
            foreach ($rows as $row) {
                $friend = new self($db);
                $friend->id = $row['id'];
                $friend->gamer1_id = $row['gamer1_id'];
                $friend->gamer2_id = $row['gamer2_id'];
                $friend->status = $row['status'];
                $friend->created_at = $row['created_at'];
                $friends[] = $friend;
            }
            return $friends;
        } catch (PDOException $e) {
            error_log("Ошибка получения друзей: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getRequests(PDO $db, $gamer_id) {
        return self::getFriends($db, $gamer_id, 'pending');
    }
    
    public static function areFriends(PDO $db, $gamer1_id, $gamer2_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM friends WHERE ((gamer1_id = ? AND gamer2_id = ?) OR (gamer1_id = ? AND gamer2_id = ?)) AND status = 'accepted'");
            $stmt->execute([$gamer1_id, $gamer2_id, $gamer2_id, $gamer1_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>