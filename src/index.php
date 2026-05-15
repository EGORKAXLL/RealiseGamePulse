<?php
session_start();

define('ROOT_PATH', __DIR__ . '/');

// Автозагрузка моделей
spl_autoload_register(function ($class) {
    $prefix = 'Models\\';
    $base_dir = ROOT_PATH . 'models/';
    if (strpos($class, $prefix) === 0) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) require $file;
    }
});

require_once ROOT_PATH . 'settings/pdo.php';
$conn = getConnection();
if (!$conn[0]) die("Ошибка БД: " . $conn[1]);
$pdo = $conn[1];

// Получаем URI без параметров
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?');
$requestUri = str_replace('/src', '', $requestUri);
$requestUri = trim($requestUri, '/');

// Если пусто — это главная (feed)
if ($requestUri == '') {
    $requestUri = 'feed';
}

// Маршруты
switch ($requestUri) {
    case 'feed':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->feed();
        break;
        
    case 'catalog':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->catalog();
        break;
        
    case 'game':
        require_once ROOT_PATH . 'controllers/GameController.php';
        $ctrl = new GameController($pdo);
        $ctrl->show($_GET['id'] ?? 0);
        break;
        
    case 'collection':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->index();
        break;
        
    case 'collection/add':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->add();
        break;
        
    case 'collection/edit':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->edit();
        break;
        
    case 'collection/delete':
        require_once ROOT_PATH . 'controllers/CollectionController.php';
        $ctrl = new CollectionController($pdo);
        $ctrl->delete();
        break;
        
    case 'review/add':
        require_once ROOT_PATH . 'controllers/ReviewController.php';
        $ctrl = new ReviewController($pdo);
        $ctrl->add();
        break;
        
    case 'review/delete':
        require_once ROOT_PATH . 'controllers/ReviewController.php';
        $ctrl = new ReviewController($pdo);
        $ctrl->delete();
        break;
        
    case 'friends':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->index();
        break;
        
    case 'friends/add':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->add();
        break;
        
    case 'friends/accept':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->accept();
        break;
        
    case 'friends/reject':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->reject();
        break;
        
    case 'friends/remove':
        require_once ROOT_PATH . 'controllers/FriendController.php';
        $ctrl = new FriendController($pdo);
        $ctrl->remove();
        break;
        
    case 'login':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->login();
        break;
        
    case 'register':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->register();
        break;
        
    case 'logout':
        require_once ROOT_PATH . 'controllers/UserController.php';
        $ctrl = new UserController($pdo);
        $ctrl->logout();
        break;
        
    // Админ-панель
    case 'admin':
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->index();
        break;
        
    case 'admin/games':
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->games();
        break;
        
    case 'admin/games/create':
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->createGame();
        break;
        
    case 'admin/games/edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->editGame($id);
        break;
        
    case 'admin/games/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->deleteGame($id);
        break;
        
    case 'admin/users':
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->users();
        break;
        
    case 'admin/users/role':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->changeRole($id);
        break;
        
    case 'admin/users/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->deleteUser($id);
        break;
        
    case 'admin/reviews':
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->reviews();
        break;
        
    case 'admin/reviews/delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once ROOT_PATH . 'controllers/AdminController.php';
        $ctrl = new AdminController($pdo);
        $ctrl->deleteReview($id);
        break;
        
    // Отчёты
    case 'report':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->index();
        break;
        
    case 'report/games':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->gamesReport();
        break;
        
    case 'report/users':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->usersReport();
        break;
        
    case 'report/popular':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->popularGamesReport();
        break;
        
    case 'report/games/export/excel':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportGamesExcel();
        break;
        
    case 'report/users/export/excel':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportUsersExcel();
        break;
        
    case 'report/popular/export/excel':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportPopularExcel();
        break;
        
    case 'report/games/export/doc':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportGamesDoc();
        break;
        
    case 'report/users/export/doc':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportUsersDoc();
        break;
        
    case 'report/popular/export/doc':
        require_once ROOT_PATH . 'controllers/ReportController.php';
        $ctrl = new ReportController($pdo);
        $ctrl->exportPopularDoc();
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Страница не найдена</h1>";
        echo "<p>Запрошенный путь: /$requestUri</p>";
        break;
}