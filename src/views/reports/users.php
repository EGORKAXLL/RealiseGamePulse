<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>👥 Отчёт по пользователям</h1>
    <div>
        <a href="/src/report/users/export/excel" class="btn">📊 Выгрузить Excel</a>
        <a href="/src/report/users/export/doc" class="btn" style="margin-left: 10px;">📝 Выгрузить Doc</a>
        <a href="/src/report" class="btn" style="margin-left: 10px;">← Назад</a>
    </div>
</div>

<p>Дата формирования: <?= date('d.m.Y H:i:s') ?></p>

<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Логин</th><th>Email</th><th>Роль</th><th>Дата регистрации</th><th>Игр в коллекции</th><th>Отзывов</th></tr>
    </thead>
    <tbody>
        <?php foreach ($reportData as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['username']) ?></td>
                <td><?= htmlspecialchars($item['email']) ?></td>
                <td><?= $item['role'] ?></td>
                <td><?= $item['created_at'] ?></td>
                <td><?= $item['games_count'] ?></td>
                <td><?= $item['reviews_count'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>