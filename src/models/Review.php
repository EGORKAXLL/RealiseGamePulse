<?php

namespace Models;

use PDO;
use PDOException;

class Review {
    public $id;
    public $gamer_id;
    public $game_id;
    public $review_text;
    public $rating;
    public $created_at;
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    public function load($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->gamer_id = $data['gamer_id'];
                $this->game_id = $data['game_id'];
                $this->review_text = $data['review_text'];
                $this->rating = $data['rating'];
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
                $stmt = $this->db->prepare("UPDATE reviews SET review_text = ?, rating = ? WHERE id = ?");
                return $stmt->execute([$this->review_text, $this->rating, $this->id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO reviews (gamer_id, game_id, review_text, rating, created_at) VALUES (?, ?, ?, ?, NOW())");
                $result = $stmt->execute([$this->gamer_id, $this->game_id, $this->review_text, $this->rating]);
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
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Ошибка удаления: " . $e->getMessage());
            return false;
        }
    }
    
    public static function getByGame(PDO $db, $game_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE game_id = ? ORDER BY created_at DESC");
            $stmt->execute([$game_id]);
            $rows = $stmt->fetchAll();
            $reviews = [];
            foreach ($rows as $row) {
                $review = new self($db);
                $review->id = $row['id'];
                $review->gamer_id = $row['gamer_id'];
                $review->game_id = $row['game_id'];
                $review->review_text = $row['review_text'];
                $review->rating = $row['rating'];
                $review->created_at = $row['created_at'];
                $reviews[] = $review;
            }
            return $reviews;
        } catch (PDOException $e) {
            error_log("Ошибка получения отзывов: " . $e->getMessage());
            return [];
        }
    }
    
    public static function getByUserAndGame(PDO $db, $gamer_id, $game_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM reviews WHERE gamer_id = ? AND game_id = ?");
            $stmt->execute([$gamer_id, $game_id]);
            $data = $stmt->fetch();
            if ($data) {
                $review = new self($db);
                $review->id = $data['id'];
                $review->gamer_id = $data['gamer_id'];
                $review->game_id = $data['game_id'];
                $review->review_text = $data['review_text'];
                $review->rating = $data['rating'];
                $review->created_at = $data['created_at'];
                return $review;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Ошибка: " . $e->getMessage());
            return null;
        }
    }
}
?>