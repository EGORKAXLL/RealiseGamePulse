<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Game.php';
require_once ROOT_PATH . 'models/Review.php';
require_once ROOT_PATH . 'models/UserGame.php';

use Models\Game;
use Models\Review;
use Models\UserGame;

class GameController extends BaseController {
    
    public function feed() {
        $allGames = Game::getAll($this->pdo);
        $recommendedGames = array_slice($allGames, 0, 8);
        $this->render('feed', [
            'pdo' => $this->pdo,
            'games' => $recommendedGames, 
            'title' => 'Лента'
        ]);
    }
    
    public function catalog() {
        $search = $_GET['search'] ?? '';
        $games = $search ? Game::search($this->pdo, $search) : Game::getAll($this->pdo);
        $this->render('catalog', [
            'pdo' => $this->pdo,
            'games' => $games, 
            'search' => $search, 
            'title' => 'Каталог'
        ]);
    }
    
    public function show($id) {
        $game = new Game($this->pdo);
        if (!$game->load($id)) die("Игра не найдена");
        $reviews = Review::getByGame($this->pdo, $id);
        $avgRating = $game->getAverageRating();
        
        $userGame = $userReview = null;
        if ($this->currentUser) {
            $userGame = UserGame::getByUserAndGame($this->pdo, $this->currentUser->id, $id);
            $userReview = Review::getByUserAndGame($this->pdo, $this->currentUser->id, $id);
        }
        
        $this->render('game', [
            'pdo' => $this->pdo,
            'game' => $game,
            'reviews' => $reviews,
            'avgRating' => $avgRating,
            'userGame' => $userGame,
            'userReview' => $userReview,
            'title' => $game->title
        ]);
    }
}