<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Gamer.php';
require_once ROOT_PATH . 'models/Friend.php';
require_once ROOT_PATH . 'models/UserGame.php';
require_once ROOT_PATH . 'models/Game.php';

use Models\Gamer;
use Models\Friend;
use Models\UserGame;
use Models\Game;

class FriendController extends BaseController {
    
    public function index() {
        $this->requireLogin();
        
        // Обработка действий
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $friend_id = (int)$_POST['friend_id'] ?? 0;
            
            if ($action === 'add') {
                if (!Friend::areFriends($this->pdo, $this->currentUser->id, $friend_id) && $this->currentUser->id != $friend_id) {
                    $f = new Friend($this->pdo);
                    $f->gamer1_id = $this->currentUser->id;
                    $f->gamer2_id = $friend_id;
                    $f->status = 'pending';
                    $f->save();
                }
            } elseif ($action === 'accept') {
                $f = new Friend($this->pdo);
                $f->load($friend_id);
                if ($f->gamer2_id == $this->currentUser->id && $f->status == 'pending') {
                    $f->status = 'accepted';
                    $f->save();
                }
            } elseif ($action === 'reject') {
                $f = new Friend($this->pdo);
                $f->load($friend_id);
                if ($f->gamer2_id == $this->currentUser->id && $f->status == 'pending') $f->delete();
            } elseif ($action === 'remove') {
                $f = new Friend($this->pdo);
                $f->load($friend_id);
                if (($f->gamer1_id == $this->currentUser->id || $f->gamer2_id == $this->currentUser->id) && $f->status == 'accepted') $f->delete();
            }
            header('Location: /src/friends');
            exit;
        }
        
        // Друзья
        $friends = Friend::getFriends($this->pdo, $this->currentUser->id, 'accepted');
        $friendsList = [];
        foreach ($friends as $f) {
            $fid = ($f->gamer1_id == $this->currentUser->id) ? $f->gamer2_id : $f->gamer1_id;
            $friend = new Gamer($this->pdo);
            $friend->load($fid);
            $friendsList[] = ['friend' => $friend, 'relation' => $f];
        }
        
        // Входящие заявки
        $incomingRequests = Friend::getRequests($this->pdo, $this->currentUser->id);
        $incomingList = [];
        foreach ($incomingRequests as $req) {
            if ($req->gamer2_id == $this->currentUser->id && $req->status == 'pending') {
                $sender = new Gamer($this->pdo);
                $sender->load($req->gamer1_id);
                $incomingList[] = ['sender' => $sender, 'request' => $req];
            }
        }
        
        // Рекомендации друзей по играм
        $recommendedFriends = $this->getRecommendedFriends();
        
        // Поиск пользователей
        $searchResults = [];
        $search = $_GET['search'] ?? '';
        if ($search) {
            $stmt = $this->pdo->prepare("SELECT * FROM gamers WHERE username LIKE ? AND id != ?");
            $stmt->execute(["%$search%", $this->currentUser->id]);
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $g = new Gamer($this->pdo);
                $g->id = $row['id'];
                $g->username = $row['username'];
                $g->email = $row['email'];
                $searchResults[] = $g;
            }
        }
        
        $this->render('friends', [
            'pdo' => $this->pdo,
            'friendsList' => $friendsList,
            'incomingList' => $incomingList,
            'recommendedFriends' => $recommendedFriends,
            'searchResults' => $searchResults,
            'search' => $search,
            'title' => 'Друзья'
        ]);
    }
    
    private function getRecommendedFriends() {
        $userGames = UserGame::getByGamer($this->pdo, $this->currentUser->id);
        if (count($userGames) == 0) return [];
        
        $userGameIds = [];
        foreach ($userGames as $ug) $userGameIds[] = $ug->game_id;
        
        $placeholders = str_repeat('?,', count($userGameIds) - 1) . '?';
        $sql = "SELECT DISTINCT gamer_id, COUNT(*) as common_games FROM user_games WHERE game_id IN ($placeholders) AND gamer_id != ? GROUP BY gamer_id ORDER BY common_games DESC LIMIT 10";
        $params = array_merge($userGameIds, [$this->currentUser->id]);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        
        $existingFriends = Friend::getFriends($this->pdo, $this->currentUser->id, 'accepted');
        $friendIds = [];
        foreach ($existingFriends as $f) $friendIds[] = ($f->gamer1_id == $this->currentUser->id) ? $f->gamer2_id : $f->gamer1_id;
        
        $pendingRequests = Friend::getRequests($this->pdo, $this->currentUser->id);
        $pendingIds = [];
        foreach ($pendingRequests as $req) {
            if ($req->gamer1_id == $this->currentUser->id && $req->status == 'pending') $pendingIds[] = $req->gamer2_id;
        }
        
        $recommendations = [];
        foreach ($rows as $row) {
            $candidateId = $row['gamer_id'];
            if (in_array($candidateId, $friendIds) || in_array($candidateId, $pendingIds)) continue;
            $candidate = new Gamer($this->pdo);
            if ($candidate->load($candidateId)) {
                $stmt2 = $this->pdo->prepare("SELECT g.title FROM user_games ug JOIN games g ON ug.game_id = g.id WHERE ug.gamer_id = ? AND ug.game_id IN ($placeholders) LIMIT 3");
                $stmt2->execute(array_merge([$candidateId], $userGameIds));
                $commonGames = $stmt2->fetchAll();
                $recommendations[] = ['user' => $candidate, 'common_games' => $row['common_games'], 'games' => $commonGames];
            }
        }
        return array_slice($recommendations, 0, 5);
    }
    
    public function add() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $friend_id = (int)$_POST['friend_id'];
            if (!Friend::areFriends($this->pdo, $this->currentUser->id, $friend_id) && $this->currentUser->id != $friend_id) {
                $f = new Friend($this->pdo);
                $f->gamer1_id = $this->currentUser->id;
                $f->gamer2_id = $friend_id;
                $f->status = 'pending';
                $f->save();
            }
        }
        header('Location: /src/friends');
        exit;
    }
    
    public function accept() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['friend_id'];
            $f = new Friend($this->pdo);
            $f->load($id);
            if ($f->gamer2_id == $this->currentUser->id && $f->status == 'pending') {
                $f->status = 'accepted';
                $f->save();
            }
        }
        header('Location: /src/friends');
        exit;
    }
    
    public function reject() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['friend_id'];
            $f = new Friend($this->pdo);
            $f->load($id);
            if ($f->gamer2_id == $this->currentUser->id && $f->status == 'pending') $f->delete();
        }
        header('Location: /src/friends');
        exit;
    }
    
    public function remove() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['friend_id'];
            $f = new Friend($this->pdo);
            $f->load($id);
            if (($f->gamer1_id == $this->currentUser->id || $f->gamer2_id == $this->currentUser->id) && $f->status == 'accepted') $f->delete();
        }
        header('Location: /src/friends');
        exit;
    }
}