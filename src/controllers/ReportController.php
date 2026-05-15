<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Game.php';
require_once ROOT_PATH . 'models/Gamer.php';
require_once ROOT_PATH . 'models/UserGame.php';

use Models\Game;
use Models\Gamer;
use Models\UserGame;

class ReportController extends BaseController {
    
    // Страница со списком отчётов
    public function index() {
        $this->requireLogin();
        $this->render('reports/index', ['title' => 'Отчёты']);
    }
    
    // Отчёт по играм (HTML)
    public function gamesReport() {
        $this->requireLogin();
        
        $games = Game::getAll($this->pdo);
        $reportData = [];
        
        foreach ($games as $game) {
            // Считаем сколько раз игра добавлена в коллекции
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $collectionsCount = $stmt->fetch()['count'];
            
            // Считаем средний рейтинг из отзывов
            $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $avgRating = round($stmt->fetch()['avg_rating'] ?? 0, 1);
            
            $reportData[] = [
                'id' => $game->id,
                'title' => $game->title,
                'developer' => $game->developer,
                'genre' => $game->genre,
                'release_year' => $game->release_year,
                'collections_count' => $collectionsCount,
                'avg_rating' => $avgRating
            ];
        }
        
        $this->render('reports/games', [
            'reportData' => $reportData,
            'title' => 'Отчёт по играм'
        ]);
    }
    
    // Отчёт по пользователям (HTML)
    public function usersReport() {
        $this->requireLogin();
        
        $users = Gamer::getAll($this->pdo);
        $reportData = [];
        
        foreach ($users as $user) {
            // Считаем сколько игр в коллекции у пользователя
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $gamesCount = $stmt->fetch()['count'];
            
            // Считаем сколько отзывов написал пользователь
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM reviews WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $reviewsCount = $stmt->fetch()['count'];
            
            $reportData[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'games_count' => $gamesCount,
                'reviews_count' => $reviewsCount
            ];
        }
        
        $this->render('reports/users', [
            'reportData' => $reportData,
            'title' => 'Отчёт по пользователям'
        ]);
    }
    
    // Отчёт по популярным играм (HTML)
    public function popularGamesReport() {
        $this->requireLogin();
        
        $stmt = $this->pdo->query("
            SELECT g.id, g.title, g.developer, g.genre, g.release_year,
                   COUNT(ug.id) as added_count,
                   COALESCE(AVG(r.rating), 0) as avg_rating
            FROM games g
            LEFT JOIN user_games ug ON g.id = ug.game_id
            LEFT JOIN reviews r ON g.id = r.game_id
            GROUP BY g.id
            ORDER BY added_count DESC
            LIMIT 10
        ");
        $reportData = $stmt->fetchAll();
        
        $this->render('reports/popular', [
            'reportData' => $reportData,
            'title' => 'Топ-10 популярных игр'
        ]);
    }
    
    // === ВЫГРУЗКА В EXCEL ===
    
    public function exportGamesExcel() {
        $this->requireLogin();
        
        $games = Game::getAll($this->pdo);
        $rows = [];
        
        foreach ($games as $game) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $collectionsCount = $stmt->fetch()['count'];
            
            $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $avgRating = round($stmt->fetch()['avg_rating'] ?? 0, 1);
            
            $rows[] = [
                $game->id,
                $game->title,
                $game->developer,
                $game->genre ?? '-',
                $game->release_year ?? '-',
                $collectionsCount,
                $avgRating
            ];
        }
        
        $this->exportToExcel($rows, 'games_report_' . date('Y-m-d'));
    }
    
    public function exportUsersExcel() {
        $this->requireLogin();
        
        $users = Gamer::getAll($this->pdo);
        $rows = [];
        
        foreach ($users as $user) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $gamesCount = $stmt->fetch()['count'];
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM reviews WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $reviewsCount = $stmt->fetch()['count'];
            
            $rows[] = [
                $user->id,
                $user->username,
                $user->email,
                $user->role,
                $user->created_at,
                $gamesCount,
                $reviewsCount
            ];
        }
        
        $this->exportToExcel($rows, 'users_report_' . date('Y-m-d'));
    }
    
    public function exportPopularExcel() {
        $this->requireLogin();
        
        $stmt = $this->pdo->query("
            SELECT g.id, g.title, g.developer, g.genre, g.release_year,
                   COUNT(ug.id) as added_count,
                   COALESCE(AVG(r.rating), 0) as avg_rating
            FROM games g
            LEFT JOIN user_games ug ON g.id = ug.game_id
            LEFT JOIN reviews r ON g.id = r.game_id
            GROUP BY g.id
            ORDER BY added_count DESC
            LIMIT 10
        ");
        $rows = $stmt->fetchAll();
        
        $exportRows = [];
        foreach ($rows as $row) {
            $exportRows[] = [
                $row['id'],
                $row['title'],
                $row['developer'],
                $row['genre'] ?? '-',
                $row['release_year'] ?? '-',
                $row['added_count'],
                round($row['avg_rating'], 1)
            ];
        }
        
        $this->exportToExcel($exportRows, 'popular_games_' . date('Y-m-d'));
    }
    
    // === ВЫГРУЗКА В DOCX ===
    
    public function exportGamesDoc() {
        $this->requireLogin();
        
        $games = Game::getAll($this->pdo);
        $html = $this->generateGamesHtmlForDoc($games);
        $this->exportToDocx($html, 'games_report_' . date('Y-m-d'));
    }
    
    public function exportUsersDoc() {
        $this->requireLogin();
        
        $users = Gamer::getAll($this->pdo);
        $html = $this->generateUsersHtmlForDoc($users);
        $this->exportToDocx($html, 'users_report_' . date('Y-m-d'));
    }
    
    public function exportPopularDoc() {
        $this->requireLogin();
        
        $stmt = $this->pdo->query("
            SELECT g.id, g.title, g.developer, g.genre, g.release_year,
                   COUNT(ug.id) as added_count,
                   COALESCE(AVG(r.rating), 0) as avg_rating
            FROM games g
            LEFT JOIN user_games ug ON g.id = ug.game_id
            LEFT JOIN reviews r ON g.id = r.game_id
            GROUP BY g.id
            ORDER BY added_count DESC
            LIMIT 10
        ");
        $rows = $stmt->fetchAll();
        
        $html = $this->generatePopularHtmlForDoc($rows);
        $this->exportToDocx($html, 'popular_games_' . date('Y-m-d'));
    }
    
    // === ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ===
    
    private function exportToExcel($data, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        
        echo '<table border="1">';
        // Заголовки
        echo '<tr>';
        foreach (array_keys($data[0] ?? []) as $header) {
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr>';
        // Данные
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }
    
    private function exportToDocx($html, $filename) {
        $fullHtml = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $filename . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                h1 { color: #333; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            ' . $html . '
        </body>
        </html>';
        
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment; filename="' . $filename . '.doc"');
        echo $fullHtml;
        exit;
    }
    
    private function generateGamesHtmlForDoc($games) {
        $html = '<h1>Отчёт по играм</h1>';
        $html .= '<p>Дата формирования: ' . date('d.m.Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<tr><th>ID</th><th>Название</th><th>Разработчик</th><th>Жанр</th><th>Год</th><th>В коллекциях</th><th>Рейтинг</th></tr>';
        
        foreach ($games as $game) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $collectionsCount = $stmt->fetch()['count'];
            
            $stmt = $this->pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE game_id = ?");
            $stmt->execute([$game->id]);
            $avgRating = round($stmt->fetch()['avg_rating'] ?? 0, 1);
            
            $html .= '<tr>';
            $html .= '<td>' . $game->id . '</td>';
            $html .= '<td>' . htmlspecialchars($game->title) . '</td>';
            $html .= '<td>' . htmlspecialchars($game->developer) . '</td>';
            $html .= '<td>' . htmlspecialchars($game->genre ?? '-') . '</td>';
            $html .= '<td>' . ($game->release_year ?? '-') . '</td>';
            $html .= '<td>' . $collectionsCount . '</td>';
            $html .= '<td>' . $avgRating . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
    
    private function generateUsersHtmlForDoc($users) {
        $html = '<h1>Отчёт по пользователям</h1>';
        $html .= '<p>Дата формирования: ' . date('d.m.Y H:i:s') . '</p>';
        $html .= '<table>';
        $html .= '<tr><th>ID</th><th>Логин</th><th>Email</th><th>Роль</th><th>Дата регистрации</th><th>Игр в коллекции</th><th>Отзывов</th></tr>';
        
        foreach ($users as $user) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM user_games WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $gamesCount = $stmt->fetch()['count'];
            
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM reviews WHERE gamer_id = ?");
            $stmt->execute([$user->id]);
            $reviewsCount = $stmt->fetch()['count'];
            
            $html .= '<tr>';
            $html .= '<td>' . $user->id . '</td>';
            $html .= '<td>' . htmlspecialchars($user->username) . '</td>';
            $html .= '<td>' . htmlspecialchars($user->email) . '</td>';
            $html .= '<td>' . $user->role . '</td>';
            $html .= '<td>' . $user->created_at . '</td>';
            $html .= '<td>' . $gamesCount . '</td>';
            $html .= '<td>' . $reviewsCount . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
    
    private function generatePopularHtmlForDoc($rows) {
        $html = '<h1>Топ-10 популярных игр</h1>';
        $html .= '<p>Дата формирования: ' . date('d.m.Y H:i:s') . '</p>';
        $html .= '<p>Отсортировано по количеству добавлений в коллекции</p>';
        $html .= '<table>';
        $html .= '<tr><th>#</th><th>Название</th><th>Разработчик</th><th>Жанр</th><th>Год</th><th>Добавлений</th><th>Рейтинг</th></tr>';
        
        $i = 1;
        foreach ($rows as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . htmlspecialchars($row['title']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['developer']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['genre'] ?? '-') . '</td>';
            $html .= '<td>' . ($row['release_year'] ?? '-') . '</td>';
            $html .= '<td>' . $row['added_count'] . '</td>';
            $html .= '<td>' . round($row['avg_rating'], 1) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        return $html;
    }
}