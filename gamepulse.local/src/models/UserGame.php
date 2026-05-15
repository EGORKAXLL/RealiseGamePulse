<?php

namespace Models;

use PDO;
use PDOException;

class UserGame {
    public $id;
    public $gamer_id;
    public $game_id;
    public $status;
    public $rating;
    public $notes;
    public $added_at;
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    // Загрузка записи по ID
    public function load($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_games WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->gamer_id = $data['gamer_id'];
                $this->game_id = $data['game_id'];
                $this->status = $data['status'];
                $this->rating = $data['rating'];
                $this->notes = $data['notes'];
                $this->added_at = $data['added_at'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка загрузки: " . $e->getMessage());
            return false;
        }
    }
    
    // Сохранение (добавление или обновление игры в коллекции)
    public function save() {
        try {
            if ($this->id) {
                $stmt = $this->db->prepare("UPDATE user_games SET status = ?, rating = ?, notes = ? WHERE id = ?");
                return $stmt->execute([$this->status, $this->rating, $this->notes, $this->id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO user_games (gamer_id, game_id, status, rating, notes, added_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $result = $stmt->execute([$this->gamer_id, $this->game_id, $this->status, $this->rating, $this->notes]);
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
    
    // Удаление игры из коллекции
    public function delete() {
        if (!$this->id) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM user_games WHERE id = ?");
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Ошибка удаления: " . $e->getMessage());
            return false;
        }
    }
    
    // Получить все игры пользователя
    public static function getByGamer(PDO $db, $gamer_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM user_games WHERE gamer_id = ? ORDER BY added_at DESC");
            $stmt->execute([$gamer_id]);
            $rows = $stmt->fetchAll();
            $userGames = [];
            foreach ($rows as $row) {
                $ug = new self($db);
                $ug->id = $row['id'];
                $ug->gamer_id = $row['gamer_id'];
                $ug->game_id = $row['game_id'];
                $ug->status = $row['status'];
                $ug->rating = $row['rating'];
                $ug->notes = $row['notes'];
                $ug->added_at = $row['added_at'];
                $userGames[] = $ug;
            }
            return $userGames;
        } catch (PDOException $e) {
            error_log("Ошибка получения коллекции: " . $e->getMessage());
            return [];
        }
    }
    
    // Получить статус игры у пользователя
    public static function getByUserAndGame(PDO $db, $gamer_id, $game_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM user_games WHERE gamer_id = ? AND game_id = ?");
            $stmt->execute([$gamer_id, $game_id]);
            $data = $stmt->fetch();
            if ($data) {
                $ug = new self($db);
                $ug->id = $data['id'];
                $ug->gamer_id = $data['gamer_id'];
                $ug->game_id = $data['game_id'];
                $ug->status = $data['status'];
                $ug->rating = $data['rating'];
                $ug->notes = $data['notes'];
                $ug->added_at = $data['added_at'];
                return $ug;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Ошибка: " . $e->getMessage());
            return null;
        }
    }
}
?>