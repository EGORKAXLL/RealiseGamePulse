<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Review.php';

use Models\Review;

class ReviewController extends BaseController {
    
    public function add() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $game_id = (int)$_POST['game_id'];
            $review_text = trim($_POST['review_text'] ?? '');
            $rating = (int)$_POST['rating'];
            if ($rating < 1 || $rating > 5) $rating = 5;
            $existing = Review::getByUserAndGame($this->pdo, $this->currentUser->id, $game_id);
            if (!$existing && !empty($review_text)) {
                $review = new Review($this->pdo);
                $review->gamer_id = $this->currentUser->id;
                $review->game_id = $game_id;
                $review->review_text = $review_text;
                $review->rating = $rating;
                $review->save();
            }
        }
        header('Location: /src/game?id=' . $game_id);
        exit;
    }
    
    public function delete() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $review = new Review($this->pdo);
            $review->load($id);
            $game_id = $review->game_id;
            if ($review->gamer_id == $this->currentUser->id || $this->currentUser->role == 'admin') {
                $review->delete();
            }
        }
        header('Location: /src/game?id=' . $game_id);
        exit;
    }
}