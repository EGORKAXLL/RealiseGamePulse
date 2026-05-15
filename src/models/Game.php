<?php

namespace Models;

use PDO;
use PDOException;

class Game {
    public $id;
    public $title;
    public $developer;
    public $genre;
    public $description;
    public $release_year;
    public $admin_id;
    
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    // Загрузка игры по ID
    public function load($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM games WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            
            if ($data) {
                $this->id = $data['id'];
                $this->title = $data['title'];
                $this->developer = $data['developer'];
                $this->genre = $data['genre'];
                $this->description = $data['description'];
                $this->release_year = $data['release_year'];
                $this->admin_id = $data['admin_id'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Ошибка загрузки игры: " . $e->getMessage());
            return false;
        }
    }
    
    // Сохранение игры
    public function save() {
        try {
            if ($this->id) {
                $stmt = $this->db->prepare("UPDATE games SET title = ?, developer = ?, genre = ?, description = ?, release_year = ?, admin_id = ? WHERE id = ?");
                return $stmt->execute([$this->title, $this->developer, $this->genre, $this->description, $this->release_year, $this->admin_id, $this->id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO games (title, developer, genre, description, release_year, admin_id) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([$this->title, $this->developer, $this->genre, $this->description, $this->release_year, $this->admin_id]);
                if ($result) {
                    $this->id = $this->db->lastInsertId();
                }
                return $result;
            }
        } catch (PDOException $e) {
            error_log("Ошибка сохранения игры: " . $e->getMessage());
            return false;
        }
    }
    
    // Удаление игры
    public function delete() {
        if (!$this->id) return false;
        try {
            $stmt = $this->db->prepare("DELETE FROM games WHERE id = ?");
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log("Ошибка удаления игры: " . $e->getMessage());
            return false;
        }
    }
    
    // Получение всех игр
    public static function getAll(PDO $db) {
        try {
            $stmt = $db->query("SELECT * FROM games ORDER BY id DESC");
            $rows = $stmt->fetchAll();
            $games = [];
            foreach ($rows as $row) {
                $game = new self($db);
                $game->id = $row['id'];
                $game->title = $row['title'];
                $game->developer = $row['developer'];
                $game->genre = $row['genre'];
                $game->description = $row['description'];
                $game->release_year = $row['release_year'];
                $game->admin_id = $row['admin_id'];
                $games[] = $game;
            }
            return $games;
        } catch (PDOException $e) {
            error_log("Ошибка получения списка игр: " . $e->getMessage());
            return [];
        }
    }
    
    // Поиск игр
    public static function search(PDO $db, $keyword) {
        try {
            $stmt = $db->prepare("SELECT * FROM games WHERE title LIKE ? OR developer LIKE ? OR genre LIKE ?");
            $searchTerm = "%$keyword%";
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
            $rows = $stmt->fetchAll();
            $games = [];
            foreach ($rows as $row) {
                $game = new self($db);
                $game->id = $row['id'];
                $game->title = $row['title'];
                $game->developer = $row['developer'];
                $game->genre = $row['genre'];
                $game->description = $row['description'];
                $game->release_year = $row['release_year'];
                $game->admin_id = $row['admin_id'];
                $games[] = $game;
            }
            return $games;
        } catch (PDOException $e) {
            error_log("Ошибка поиска игр: " . $e->getMessage());
            return [];
        }
    }
    
    // Получение пути к обложке
public function getCoverUrl() {
    $target_dir = ROOT_PATH . 'uploads/games/covers/';
    $files = glob($target_dir . $this->id . '.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    if (!empty($files)) {
        return '/src/uploads/games/covers/' . basename($files[0]);
    }
    return '/src/uploads/games/covers/default.jpg';
}
    
    // Получение среднего рейтинга игры
    public function getAverageRating() {
        try {
            $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE game_id = ?");
            $stmt->execute([$this->id]);
            $result = $stmt->fetch();
            return $result['avg_rating'] ? round($result['avg_rating'], 1) : 0;
        } catch (PDOException $e) {
            error_log("Ошибка получения рейтинга: " . $e->getMessage());
            return 0;
        }
    }
}

?>