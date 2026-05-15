GamePulse



Персонализированная платформа для рекомендаций и каталогизации видеоигр.



Команда:

Синейкин Павел Романович - Backend-разработчик, БД, авторизация, отчёты

Жилин Егор Сергеевич - Frontend-разработчик, UI/UX, каталог, коллекция

Логинов Андрей Витальевич - DevOps, Docker, документация, друзья



Технологический стек:

Backend: PHP 8.2, MySQL 8.0, PDO

Frontend: HTML5, CSS3, JavaScript

Инфраструктура: Apache, Docker, Docker Compose



Установка и запуск



Локальный запуск (OpenPanel):

1\. Скопируйте папку src в D:\\OSPanel\\home\\gamepulse.local\\

2\. Запустите OpenPanel

3\. Откройте http://gamepulse.local/src/



Запуск через Docker:

git clone https://github.com/your-username/gamepulse.git

cd gamepulse

docker-compose up -d



Приложение: http://localhost:8080/src/

phpMyAdmin: http://localhost:8081 (сервер: db, пользователь: root, пароль: root)



Тестовые аккаунты:

Администратор: admin / admin123

Пользователь: gamer / gamer123



Документация:

Руководство пользователя: docs/final/user\_guide.md

Руководство разработчика: docs/final/dev\_guide.md

Инструкция по развёртыванию: docs/final/deploy.md

Анализ расхождений: docs/final/deviations.md



Структура проекта:

gamepulse.local/

├── src/

│   ├── controllers/

│   ├── models/

│   ├── views/

│   ├── settings/

│   ├── uploads/

│   └── index.php

├── docs/

├── Dockerfile

├── docker-compose.yml

└── README.md

