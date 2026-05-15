<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamePulse - <?= $title ?? 'Игровая платформа' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        body {
            background: #1a1e2b;
            color: #eef2ff;
        }
        .header {
            background: #0f1219;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #2c3142;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #7c9eff;
            text-decoration: none;
        }
        .nav {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .nav a {
            color: #b9c8ff;
            text-decoration: none;
        }
        .nav a:hover {
            color: white;
        }
        .nav-admin {
            color: #ffaa66 !important;
            font-weight: bold;
        }
        .nav-admin:hover {
            color: #ffcc88 !important;
        }
        .user-info {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .user-info a {
            color: #b9c8ff;
            text-decoration: none;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #2a3457;
        }
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 30px;
            min-height: calc(100vh - 150px);
        }
        .btn {
            background: #2a3457;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn:hover {
            background: #3e4b74;
        }
        .footer {
            text-align: center;
            padding: 30px;
            color: #6a7aa3;
            border-top: 1px solid #252d44;
        }
        .section {
            background: #141b2b;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 35px;
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #b7cdff;
        }
        .game-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .game-card {
            background: #1e263b;
            border-radius: 16px;
            padding: 15px;
            width: 200px;
            border: 1px solid #36405e;
        }
        .game-card h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }
        .game-cover {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 12px;
            background: #2a3457;
        }
        .game-meta {
            color: #8e9fc7;
            font-size: 13px;
        }
        .stars {
            color: #ffc107;
            margin: 8px 0;
        }
        .error {
            color: #ff8a7a;
            padding: 10px;
            background: #2a1a1a;
            border-radius: 8px;
            margin: 10px 0;
        }
        .success {
            color: #7cff9e;
            padding: 10px;
            background: #1a2a1a;
            border-radius: 8px;
            margin: 10px 0;
        }
        /* Админ-таблицы */
        .admin-table {
            width: 100%;
            background: #141b2b;
            border-radius: 16px;
            overflow: hidden;
            border-collapse: collapse;
        }
        .admin-table th {
            background: #1e263b;
            padding: 12px;
            text-align: left;
        }
        .admin-table td {
            padding: 12px;
            border-bottom: 1px solid #2a334c;
        }
        .admin-table tr:hover {
            background: #1a2135;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="/src/" class="logo">GamePulse</a>
        <div class="nav">
            <a href="/src/">Лента</a>
            <a href="/src/catalog">Каталог</a>
            <?php if ($currentUser): ?>
                <a href="/src/collection">Моя коллекция</a>
                <a href="/src/friends">Друзья</a>
                <a href="/src/report">📊 Отчёты</a>
                <?php if ($currentUser->role == 'admin'): ?>
                    <a href="/src/admin" class="nav-admin">👑 Админ-панель</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <?php if ($currentUser): ?>
                <?php 
                    $avatarPath = '/src/uploads/avatars/' . $currentUser->id . '.jpg';
                    if (!file_exists(ROOT_PATH . 'uploads/avatars/' . $currentUser->id . '.jpg')) {
                        $avatarPath = '/src/uploads/avatars/default.png';
                    }
                ?>
                <img src="<?= $avatarPath ?>" class="user-avatar" alt="avatar">
                <span>👤 <?= htmlspecialchars($currentUser->username) ?></span>
                <a href="/src/logout">Выход</a>
            <?php else: ?>
                <a href="/src/login">Вход</a>
                <a href="/src/register">Регистрация</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?= $content ?>
    </div>

    <div class="footer">
        © 2026 GamePulse · Помогаем находить игры по вкусу
    </div>
</body>
</html>