<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1>Управление играми</h1>
    <a href="/src/admin/games/create" class="btn" style="background:#2a5a2a;">➕ Добавить игру</a>
</div>

<table style="width:100%; background:#141b2b; border-radius:16px; overflow:hidden;">
    <thead style="background:#1e263b;">
        <tr><th style="padding:12px;">ID</th><th>Название</th><th>Разработчик</th><th>Жанр</th><th>Год</th><th>Действия</th></tr>
    </thead>
    <tbody>
        <?php foreach ($games as $game): ?>
            <tr style="border-bottom:1px solid #2a334c;">
                <td style="padding:12px;"><?= $game->id ?></td>
                <td><?= htmlspecialchars($game->title) ?></td>
                <td><?= htmlspecialchars($game->developer) ?></td>
                <td><?= htmlspecialchars($game->genre) ?></td>
                <td><?= $game->release_year ?></td>
                <td>
                    <a href="/src/admin/games/edit?id=<?= $game->id ?>" class="btn" style="padding:4px 12px;">✏️</a>
                    <a href="/src/admin/games/delete?id=<?= $game->id ?>" class="btn" style="background:#5a2a2a; padding:4px 12px;" onclick="return confirm('Удалить игру?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>