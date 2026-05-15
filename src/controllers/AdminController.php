<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Game.php';
require_once ROOT_PATH . 'models/Gamer.php';
require_once ROOT_PATH . 'models/Review.php';
require_once ROOT_PATH . 'models/Upload.php';

use Models\Game;
use Models\Gamer;
use Models\Review;
use Models\Upload;

class AdminController extends BaseController {
    
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->requireAdmin();
    }
    
    protected function requireAdmin() {
        $this->requireLogin();
        if ($this->currentUser->role !== 'admin') {
            die("Доступ запрещён. Требуются права администратора.");
        }
    }
    
    // Главная админ-панели
    public function index() {
        // Количество игр
        $gamesCount = count(Game::getAll($this->pdo));
        
        // Количество пользователей
        $usersCount = count(Gamer::getAll($this->pdo));
        
        // Количество отзывов (правильный запрос)
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM reviews");
        $reviewsCount = $stmt->fetch()['total'];
        
        $stats = [
            'games' => $gamesCount,
            'users' => $usersCount,
            'reviews' => $reviewsCount
        ];
        
        $this->render('admin/index', [
            'stats' => $stats,
            'title' => 'Админ-панель'
        ]);
    }
    
    // Управление играми
    public function games() {
        $games = Game::getAll($this->pdo);
        $this->render('admin/games', [
            'games' => $games,
            'title' => 'Управление играми'
        ]);
    }
    
    // Форма добавления игры
    public function createGame() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game = new Game($this->pdo);
            $game->title = $_POST['title'];
            $game->developer = $_POST['developer'];
            $game->genre = $_POST['genre'];
            $game->description = $_POST['description'];
            $game->release_year = $_POST['release_year'];
            $game->admin_id = $this->currentUser->id;
            
            if ($game->save()) {
                // Загружаем обложку
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
                    Upload::uploadGameCover($_FILES['cover_image'], $game->id);
                }
                header('Location: /src/admin/games');
                exit;
            }
        }
        
        $this->render('admin/game_form', [
            'game' => null,
            'title' => 'Добавить игру'
        ]);
    }
    
    // Форма редактирования игры
    public function editGame($id) {
        $game = new Game($this->pdo);
        if (!$game->load($id)) {
            die("Игра не найдена");
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game->title = $_POST['title'];
            $game->developer = $_POST['developer'];
            $game->genre = $_POST['genre'];
            $game->description = $_POST['description'];
            $game->release_year = $_POST['release_year'];
            $game->save();
            
            // Загружаем новую обложку
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
                Upload::deleteGameCover($id);
                Upload::uploadGameCover($_FILES['cover_image'], $id);
            }
            
            header('Location: /src/admin/games');
            exit;
        }
        
        $this->render('admin/game_form', [
            'game' => $game,
            'title' => 'Редактировать игру'
        ]);
    }
    
    // Удаление игры
    public function deleteGame($id) {
        $game = new Game($this->pdo);
        if ($game->load($id)) {
            Upload::deleteGameCover($id);
            $game->delete();
        }
        header('Location: /src/admin/games');
        exit;
    }
    
    // Управление пользователями
    public function users() {
        $users = Gamer::getAll($this->pdo);
        $this->render('admin/users', [
            'users' => $users,
            'title' => 'Управление пользователями'
        ]);
    }
    
    // Изменение роли пользователя
    public function changeRole($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gamer = new Gamer($this->pdo);
            if ($gamer->load($id)) {
                $gamer->role = $_POST['role'];
                $gamer->save();
            }
        }
        header('Location: /src/admin/users');
        exit;
    }
    
    // Удаление пользователя
    public function deleteUser($id) {
        if ($id != $this->currentUser->id) {
            $gamer = new Gamer($this->pdo);
            $gamer->load($id);
            $gamer->delete();
        }
        header('Location: /src/admin/users');
        exit;
    }
    
    // Модерация отзывов
    public function reviews() {
        $stmt = $this->pdo->query("SELECT r.*, g.username, ga.title FROM reviews r 
                                   JOIN gamers g ON r.gamer_id = g.id 
                                   JOIN games ga ON r.game_id = ga.id 
                                   ORDER BY r.created_at DESC");
        $reviews = $stmt->fetchAll();
        
        $this->render('admin/reviews', [
            'reviews' => $reviews,
            'title' => 'Модерация отзывов'
        ]);
    }
    
    // Удаление отзыва
    public function deleteReview($id) {
        $review = new Review($this->pdo);
        $review->load($id);
        $review->delete();
        header('Location: /src/admin/reviews');
        exit;
    }
}