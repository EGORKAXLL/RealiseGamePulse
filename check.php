<?php

define('ROOT_PATH', __DIR__ . '/src/');

include_once ROOT_PATH . 'settings/pdo.php';
include_once ROOT_PATH . 'models/Game.php';
include_once ROOT_PATH . 'models/Gamer.php';
include_once ROOT_PATH . 'models/UserGame.php';
include_once ROOT_PATH . 'models/Review.php';
include_once ROOT_PATH . 'models/Friend.php';

use Models\Game;
use Models\Gamer;
use Models\UserGame;
use Models\Review;
use Models\Friend;

echo "<h1>GamePulse - Проверка моделей</h1>";

$conn = getConnection();

if (!$conn[0]) {
    die("<p style='color:red'>Ошибка: " . $conn[1] . "</p>");
}

$pdo = $conn[1];
echo "<p style='color:green'>✅ База данных подключена</p>";

// 1. Проверка Game
echo "<h2>1. Игры в каталоге:</h2>";
$games = Game::getAll($pdo);
if (count($games) > 0) {
    echo "<ul>";
    foreach ($games as $game) {
        echo "<li>{$game->title} - {$game->developer} ({$game->release_year})</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>Нет игр. Добавьте через phpMyAdmin.</p>";
}

// 2. Проверка Gamer
echo "<h2>2. Пользователи:</h2>";
$gamers = Gamer::getAll($pdo);
if (count($gamers) > 0) {
    echo "<ul>";
    foreach ($gamers as $gamer) {
        echo "<li>{$gamer->username} ({$gamer->email}) - {$gamer->role}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>Нет пользователей.</p>";
}

// 3. Проверка UserGame (коллекция)
echo "<h2>3. Коллекции пользователей:</h2>";
if (count($gamers) > 0 && count($games) > 0) {
    $gamerId = $gamers[0]->id;
    $gameId = $games[0]->id;
    
    // Проверяем есть ли уже игра в коллекции
    $existing = UserGame::getByUserAndGame($pdo, $gamerId, $gameId);
    
    if (!$existing) {
        // Добавляем тестовую игру в коллекцию
        $userGame = new UserGame($pdo);
        $userGame->gamer_id = $gamerId;
        $userGame->game_id = $gameId;
        $userGame->status = 'playing';
        $userGame->rating = 5;
        $userGame->notes = 'Тестовая запись';
        
        if ($userGame->save()) {
            echo "<p style='color:green'>✅ Добавлена игра в коллекцию пользователя {$gamers[0]->username}</p>";
        } else {
            echo "<p style='color:red'>❌ Ошибка добавления в коллекцию</p>";
        }
    } else {
        echo "<p>Игра уже в коллекции пользователя {$gamers[0]->username}</p>";
    }
    
    // Показываем коллекцию
    $userGames = UserGame::getByGamer($pdo, $gamerId);
    if (count($userGames) > 0) {
        echo "<ul>";
        foreach ($userGames as $ug) {
            // Получаем название игры
            $game = new Game($pdo);
            $game->load($ug->game_id);
            echo "<li>{$game->title} - статус: {$ug->status}, оценка: {$ug->rating}</li>";
        }
        echo "</ul>";
    }
}

// 4. Проверка Review
echo "<h2>4. Отзывы:</h2>";
if (count($gamers) > 0 && count($games) > 0) {
    $gamerId = $gamers[0]->id;
    $gameId = $games[0]->id;
    
    $existingReview = Review::getByUserAndGame($pdo, $gamerId, $gameId);
    
    if (!$existingReview) {
        $review = new Review($pdo);
        $review->gamer_id = $gamerId;
        $review->game_id = $gameId;
        $review->review_text = "Отличная игра! Рекомендую!";
        $review->rating = 5;
        
        if ($review->save()) {
            echo "<p style='color:green'>✅ Добавлен отзыв на игру {$games[0]->title}</p>";
        } else {
            echo "<p style='color:red'>❌ Ошибка добавления отзыва</p>";
        }
    } else {
        echo "<p>Отзыв на эту игру уже есть</p>";
    }
    
    // Показываем отзывы на игру
    $reviews = Review::getByGame($pdo, $gameId);
    if (count($reviews) > 0) {
        echo "<ul>";
        foreach ($reviews as $rev) {
            echo "<li>Оценка: {$rev->rating}/5 - {$rev->review_text}</li>";
        }
        echo "</ul>";
    }
}

// 5. Проверка Friend
echo "<h2>5. Друзья:</h2>";
if (count($gamers) >= 2) {
    $gamer1 = $gamers[0];
    $gamer2 = $gamers[1];
    
    $areFriends = Friend::areFriends($pdo, $gamer1->id, $gamer2->id);
    
    if (!$areFriends && $gamer1->id != $gamer2->id) {
        $friend = new Friend($pdo);
        $friend->gamer1_id = $gamer1->id;
        $friend->gamer2_id = $gamer2->id;
        $friend->status = 'accepted';
        
        if ($friend->save()) {
            echo "<p style='color:green'>✅ {$gamer1->username} и {$gamer2->username} теперь друзья</p>";
        }
    } elseif ($gamer1->id != $gamer2->id) {
        echo "<p>{$gamer1->username} и {$gamer2->username} уже друзья</p>";
    }
    
    // Показываем список друзей
    $friends = Friend::getFriends($pdo, $gamer1->id);
    if (count($friends) > 0) {
        echo "<ul>";
        foreach ($friends as $f) {
            $friendId = ($f->gamer1_id == $gamer1->id) ? $f->gamer2_id : $f->gamer1_id;
            $friend = new Gamer($pdo);
            $friend->load($friendId);
            echo "<li>Друг: {$friend->username}</li>";
        }
        echo "</ul>";
    }
}

echo "<hr>";
echo "<p>✅ Все модели работают!</p>";
?>