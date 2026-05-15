<?php

namespace Models;

class Upload {
    
    // Загрузка обложки игры
    public static function uploadGameCover($file, $game_id) {
        $target_dir = ROOT_PATH . 'uploads/games/covers/';
        
        // Создаём папку если нет
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($extension, $allowed)) {
            return ['success' => false, 'error' => 'Недопустимый формат файла'];
        }
        
        $filename = $game_id . '.' . $extension;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'error' => 'Ошибка загрузки файла'];
    }
    
    // Загрузка аватара пользователя
    public static function uploadAvatar($file, $user_id) {
        $target_dir = ROOT_PATH . 'uploads/avatars/';
        
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($extension, $allowed)) {
            return ['success' => false, 'error' => 'Недопустимый формат файла'];
        }
        
        $filename = $user_id . '.' . $extension;
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'error' => 'Ошибка загрузки файла'];
    }
    
    // Удаление обложки
    public static function deleteGameCover($game_id) {
        $target_dir = ROOT_PATH . 'uploads/games/covers/';
        $files = glob($target_dir . $game_id . '.*');
        foreach ($files as $file) {
            unlink($file);
        }
        return true;
    }
    
    // Получение пути к обложке
    public static function getGameCover($game_id) {
        $target_dir = ROOT_PATH . 'uploads/games/covers/';
        $files = glob($target_dir . $game_id . '.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        if (!empty($files)) {
            return '/src/uploads/games/covers/' . basename($files[0]);
        }
        return '/src/uploads/games/covers/default.jpg';
    }
}