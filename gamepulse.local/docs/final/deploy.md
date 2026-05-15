Инструкция по развёртыванию GamePulse



1\. Требования к серверу

ОС: Windows 10/11, Linux, macOS

Docker: Docker Desktop 4.0+

Память: 4 GB RAM

Порты: 8080, 8081



2\. Развёртывание

Выполнить команды:

git clone https://github.com/EGORKAXLL/RealiseGamePulse

cd "C:\Users\user\RealiseGamePulse"

docker-compose up -d

(См. скриншот 26) Результат выполнения команд в терминале.



3\. Доступ

(См. скриншот 27) Приложение: http://localhost:8080/src/

(См. скриншот 28) phpMyAdmin: http://localhost:8081 (сервер: db, пользователь: root, пароль: root)



4\. Создание администратора

В phpMyAdmin выполнить SQL:

INSERT INTO gamers (username, email, password\_hash, role) VALUES ('admin', 'admin@gamepulse.com', '$2y$10$5x5x5x5x5x5x5x5x5x5x5x', 'admin');

Пароль для входа: admin123



5\. Резервное копирование

Экспорт: docker exec gamepulse\_db mysqldump -uroot -proot gamepulse > backup.sql

Импорт: docker exec -i gamepulse\_db mysql -uroot -proot gamepulse < backup.sql



6\. Устранение неполадок

\- Порт занят: измените порты в docker-compose.yml на 8082:80 и 8083:80.

\- Ошибка подключения к БД: проверьте src/settings/pdo.php, host должен быть 'db'.

\- Полный сброс: docker-compose down -v; docker-compose up -d

(См. скриншот 29) Логи ошибок при необходимости.

