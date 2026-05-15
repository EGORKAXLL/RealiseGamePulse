<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>🏆 Топ-10 популярных игр</h1>
    <div>
        <a href="/src/report/popular/export/excel" class="btn">📊 Выгрузить Excel</a>
        <a href="/src/report/popular/export/doc" class="btn" style="margin-left: 10px;">📝 Выгрузить Doc</a>
        <a href="/src/report" class="btn" style="margin-left: 10px;">← Назад</a>
    </div>
</div>

<p>Дата формирования: <?= date('d.m.Y H:i:s') ?></p>
<p>Отсортировано по количеству добавлений в коллекции</p>

<table class="admin-table">
    <thead>
        <tr><th>#</th><th>Название</th><th>Разработчик</th><th>Жанр</th><th>Год</th><th>Добавлений</th><th>Рейтинг</th></tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($reportData as $item): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= htmlspecialchars($item['developer']) ?></td>
                <td><?= htmlspecialchars($item['genre'] ?? '-') ?></td>
                <td><?= $item['release_year'] ?? '-' ?></td>
                <td><?= $item['added_count'] ?></td>
                <td><?= round($item['avg_rating'], 1) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>