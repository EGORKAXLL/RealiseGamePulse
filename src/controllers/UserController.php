<?php

require_once ROOT_PATH . 'controllers/BaseController.php';
require_once ROOT_PATH . 'models/Gamer.php';

use Models\Gamer;

class UserController extends BaseController {
    
    public function login() {
        if ($this->currentUser) { header('Location: /src/'); exit; }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $gamer = new Gamer($this->pdo);
            if ($gamer->loadByUsername($username) && $gamer->verifyPassword($password)) {
                $_SESSION['user_id'] = $gamer->id;
                $_SESSION['username'] = $gamer->username;
                $_SESSION['role'] = $gamer->role;
                header('Location: /src/');
                exit;
            } else { $error = 'Неверный логин или пароль'; }
        }
        $this->render('login', ['error' => $error, 'title' => 'Вход']);
    }
    
    public function register() {
        if ($this->currentUser) { header('Location: /src/'); exit; }
        $error = $success = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            
            if (empty($username)) $error = 'Введите логин';
            elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) $error = 'Логин 3-20 символов (буквы, цифры, _)';
            elseif (empty($email)) $error = 'Введите email';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Некорректный email';
            elseif (empty($password)) $error = 'Введите пароль';
            elseif (strlen($password) < 6) $error = 'Пароль не менее 6 символов';
            elseif ($password !== $confirm) $error = 'Пароли не совпадают';
            
            if (empty($error)) {
                $gamer = new Gamer($this->pdo);
                $gamer->username = $username;
                $gamer->email = $email;
                $gamer->setPassword($password);
                $gamer->role = 'gamer';
                if ($gamer->save()) $success = 'Регистрация успешна! Теперь можете войти.';
                else $error = 'Ошибка регистрации. Логин или email уже существует.';
            }
        }
        $this->render('register', ['error' => $error, 'success' => $success, 'title' => 'Регистрация']);
    }
    
    public function logout() {
        session_destroy();
        header('Location: /src/login');
        exit;
    }
}