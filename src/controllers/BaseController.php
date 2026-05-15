<?php

require_once ROOT_PATH . 'models/Gamer.php';

use Models\Gamer;

class BaseController {
    protected $pdo;
    protected $currentUser;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (isset($_SESSION['user_id'])) {
            $this->currentUser = new Gamer($pdo);
            $this->currentUser->load($_SESSION['user_id']);
        }
    }
    
    protected function requireLogin() {
        if (!$this->currentUser) {
            header('Location: /src/login');
            exit;
        }
    }
    
    protected function render($view, $data = []) {
        extract($data);
        $currentUser = $this->currentUser;
        ob_start();
        require_once ROOT_PATH . 'views/' . $view . '.php';
        $content = ob_get_clean();
        require_once ROOT_PATH . 'views/layout.php';
    }
}