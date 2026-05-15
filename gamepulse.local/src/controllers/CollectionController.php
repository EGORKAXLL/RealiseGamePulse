<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/UserGame.php';
require_once ROOT_PATH . 'models/Game.php';

use Models\UserGame;
use Models\Game;

class CollectionController extends BaseController {
    
    public function index() {
        $this->requireLogin();
        $userGames = UserGame::getByGamer($this->pdo, $this->currentUser->id);
        $games = [];
        foreach ($userGames as $ug) {
            $game = new Game($this->pdo);
            $game->load($ug->game_id);
            $games[] = ['user_game' => $ug, 'game' => $game];
        }
        $this->render('collection', ['games' => $games, 'title' => 'Моя коллекция']);
    }
    
    public function add() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game_id = (int)$_POST['game_id'];
            $status = $_POST['status'] ?? 'planned';
            $rating = $_POST['rating'] ?? null;
            
            // Преобразуем пустую строку в NULL для корректной вставки в БД
            if ($rating === '') {
                $rating = null;
            }
            
            // Проверяем, нет ли уже такой игры в коллекции
            $existing = UserGame::getByUserAndGame($this->pdo, $this->currentUser->id, $game_id);
            
            if (!$existing) {
                $ug = new UserGame($this->pdo);
                $ug->gamer_id = $this->currentUser->id;
                $ug->game_id = $game_id;
                $ug->status = $status;
                $ug->rating = $rating;
                $ug->notes = '';
                $ug->save();
            }
        }
        
        header('Location: /src/game?id=' . $game_id);
        exit;
    }
    
    public function edit() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $status = $_POST['status'] ?? 'planned';
            $rating = $_POST['rating'] ?? null;
            
            // Преобразуем пустую строку в NULL
            if ($rating === '') {
                $rating = null;
            }
            
            $ug = new UserGame($this->pdo);
            $ug->load($id);
            
            if ($ug->gamer_id == $this->currentUser->id) {
                $ug->status = $status;
                $ug->rating = $rating;
                $ug->save();
            }
        }
        
        header('Location: /src/collection');
        exit;
    }
    
    public function delete() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $ug = new UserGame($this->pdo);
            $ug->load($id);
            
            if ($ug->gamer_id == $this->currentUser->id) {
                $ug->delete();
            }
        }
        
        header('Location: /src/collection');
        exit;
    }
}