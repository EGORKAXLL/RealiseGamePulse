<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>📋 Отчёт по играм</h1>
    <div>
        <a href="/src/report/games/export/excel" class="btn">📊 Выгрузить Excel</a>
        <a href="/src/report/games/export/doc" class="btn" style="margin-left: 10px;">📝 Выгрузить Doc</a>
        <a href="/src/report" class="btn" style="margin-left: 10px;">← Назад</a>
    </div>
</div>

<p>Дата формирования: <?= date('d.m.Y H:i:s') ?></p>

<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Название</th><th>Разработчик</th><th>Жанр</th><th>Год</th><th>В коллекциях</th><th>Рейтинг</th></tr>
    </thead>
    <tbody>
        <?php foreach ($reportData as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['developer']) ?></td>
                <td><?= htmlspecialchars($item['genre'] ?? '-') ?></td>
                <td><?= $item['release_year'] ?? '-' ?></td>
                <td><?= $item['collections_count'] ?></td>
                <td><?= $item['avg_rating'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>