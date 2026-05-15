<div class="greeting" style="font-size:28px; margin-bottom:30px;">
    👋 Привет, <strong><?= htmlspecialchars($currentUser->username ?? 'Гость') ?></strong>!
</div>

<div class="section">
    <h2 class="section-title">🎯 Рекомендуем для вас</h2>
    <div class="game-grid">
        <?php foreach ($games as $game): ?>
            <div class="game-card">
                <div style="height:120px; background:#2a3457; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:48px;">🎮</div>
                <h3><?= htmlspecialchars($game->title) ?></h3>
                <div class="game-meta"><?= htmlspecialchars($game->developer) ?> • <?= $game->release_year ?></div>
                <a href="/src/game?id=<?= $game->id ?>" class="btn" style="display:block; text-align:center; margin-top:10px;">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>