<h1>📊 Отчёты GamePulse</h1>

<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 30px;">
    <!-- Отчёт по играм -->
    <div style="background: #141b2b; border-radius: 20px; padding: 25px; width: 300px;">
        <div style="font-size: 48px;">🎮</div>
        <h2>Отчёт по играм</h2>
        <p>Полный список всех игр с количеством добавлений в коллекции и средним рейтингом.</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="/src/report/games" class="btn">📄 Просмотреть</a>
            <a href="/src/report/games/export/excel" class="btn">📊 Excel</a>
            <a href="/src/report/games/export/doc" class="btn">📝 Doc</a>
        </div>
    </div>
    
    <!-- Отчёт по пользователям -->
    <div style="background: #141b2b; border-radius: 20px; padding: 25px; width: 300px;">
        <div style="font-size: 48px;">👥</div>
        <h2>Отчёт по пользователям</h2>
        <p>Список пользователей с количеством игр в коллекции и написанных отзывов.</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="/src/report/users" class="btn">📄 Просмотреть</a>
            <a href="/src/report/users/export/excel" class="btn">📊 Excel</a>
            <a href="/src/report/users/export/doc" class="btn">📝 Doc</a>
        </div>
    </div>
    
    <!-- Отчёт по популярным играм -->
    <div style="background: #141b2b; border-radius: 20px; padding: 25px; width: 300px;">
        <div style="font-size: 48px;">🏆</div>
        <h2>Топ-10 популярных игр</h2>
        <p>Самые добавляемые игры в коллекции пользователей.</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="/src/report/popular" class="btn">📄 Просмотреть</a>
            <a href="/src/report/popular/export/excel" class="btn">📊 Excel</a>
            <a href="/src/report/popular/export/doc" class="btn">📝 Doc</a>
        </div>
    </div>
</div>