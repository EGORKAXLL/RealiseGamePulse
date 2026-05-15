<h1 style="margin-bottom: 20px;">Каталог игр</h1>

<form method="GET" style="margin-bottom: 30px;">
    <input type="text" name="search" placeholder="Поиск по названию, разработчику..." value="<?= htmlspecialchars($search) ?>" style="padding:12px; width:300px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    <button type="submit" class="btn" style="margin-left:10px;">🔍 Найти</button>
    <?php if ($search): ?>
        <a href="/src/catalog" style="color:#7c9eff; margin-left:15px;">Сбросить</a>
    <?php endif; ?>
</form>

<div class="game-grid">
    <?php if (count($games) > 0): ?>
        <?php foreach ($games as $game): ?>
            <div class="game-card">
                <div style="height:120px; background:#2a3457; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:48px;">🎮</div>
                <h3><?= htmlspecialchars($game->title) ?></h3>
                <div class="game-meta"><?= htmlspecialchars($game->developer) ?> • <?= $game->release_year ?></div>
                <div class="game-meta">Жанр: <?= htmlspecialchars($game->genre) ?></div>
                <a href="/src/game?id=<?= $game->id ?>" class="btn" style="display:block; text-align:center; margin-top:10px;">Подробнее</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Игры не найдены</p>
    <?php endif; ?>
</div>