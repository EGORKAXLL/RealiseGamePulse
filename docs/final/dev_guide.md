Руководство разработчика GamePulse

1. Структура проекта
(См. скриншот 16) Структура папок:
src/
├── controllers/          - Контроллеры
├── models/               - Модели
├── views/                - Шаблоны
├── settings/pdo.php      - Подключение к БД
├── uploads/              - Загруженные файлы
└── index.php             - Роутер

2. Архитектура и паттерны
- MVC: (См. скриншот 17) Пример кода контроллера (GameController.php).
- Active Record: (См. скриншот 18) Пример кода модели (Game.php).
- Front Controller: (См. скриншот 19) Пример кода роутера (index.php).
- Базовый контроллер: (См. скриншот 20) Пример кода BaseController.php с методами requireLogin, requireAdmin, render.

3. Технологический стек
PHP 8.2 (популярность, ООП), MySQL 8.0 (надёжность), PDO (защита от SQL-инъекций), Apache (mod_rewrite), Docker (унификация окружения).

4. Безопасность
- Защита от SQL-инъекций: (См. скриншот 21) использование PDO prepare.
- Хеширование паролей: (См. скриншот 22) password_hash и password_verify.
- Защита от XSS: (См. скриншот 23) htmlspecialchars при выводе.
- Авторизация и роли: (См. скриншот 24) requireLogin и requireAdmin.

5. Ошибки и решения
404: проверьте маршрут в index.php.
Ошибка БД: в Docker хост = db, проверьте pdo.php.
Call to undefined method: добавьте метод в модель.
Белый экран: включите error_reporting(E_ALL).

6. Работа с Docker
(См. скриншот 25) Docker Desktop с запущенными контейнерами (web, db, phpmyadmin).
Переменные окружения в docker-compose.yml: DB_HOST=db, DB_NAME=gamepulse, DB_USER=root, DB_PASS=root.
Команды: docker-compose up -d, down, logs -f, exec -it gamepulse_web bash, exec -it gamepulse_db mysql -uroot -proot.