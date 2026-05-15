<h1 style="margin-bottom: 20px;">Моя коллекция</h1>

<?php if (count($games) > 0): ?>
    <?php foreach ($games as $item): ?>
        <div style="background:#141b2b; border-radius:16px; padding:20px; margin-bottom:15px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
            <div>
                <h3><?= htmlspecialchars($item['game']->title) ?></h3>
                <div class="game-meta"><?= htmlspecialchars($item['game']->developer) ?> • <?= $item['game']->release_year ?></div>
                <div class="game-meta">Оценка: <?= $item['user_game']->rating ? $item['user_game']->rating . '/5' : 'не оценено' ?></div>
            </div>
            <div>
                <span style="padding:5px 15px; border-radius:20px; font-size:14px; background:<?= $item['user_game']->status == 'playing' ? '#2a5a2a' : ($item['user_game']->status == 'completed' ? '#2a4a7a' : '#7a6a2a') ?>;">
                    <?= $item['user_game']->status == 'playing' ? '🎮 Играю' : ($item['user_game']->status == 'completed' ? '✅ Пройдено' : '📋 В планах') ?>
                </span>
            </div>
            <form action="/src/collection/edit" method="POST" style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                <input type="hidden" name="id" value="<?= $item['user_game']->id ?>">
                <select name="status" style="padding:5px; background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white;">
                    <option value="planned" <?= $item['user_game']->status == 'planned' ? 'selected' : '' ?>>В планах</option>
                    <option value="playing" <?= $item['user_game']->status == 'playing' ? 'selected' : '' ?>>Играю</option>
                    <option value="completed" <?= $item['user_game']->status == 'completed' ? 'selected' : '' ?>>Пройдено</option>
                </select>
                <select name="rating" style="padding:5px; background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white;">
                    <option value="">Не оценено</option>
                    <option value="1" <?= $item['user_game']->rating == 1 ? 'selected' : '' ?>>1 ★</option>
                    <option value="2" <?= $item['user_game']->rating == 2 ? 'selected' : '' ?>>2 ★★</option>
                    <option value="3" <?= $item['user_game']->rating == 3 ? 'selected' : '' ?>>3 ★★★</option>
                    <option value="4" <?= $item['user_game']->rating == 4 ? 'selected' : '' ?>>4 ★★★★</option>
                    <option value="5" <?= $item['user_game']->rating == 5 ? 'selected' : '' ?>>5 ★★★★★</option>
                </select>
                <button type="submit" class="btn">Обновить</button>
            </form>
            <form action="/src/collection/delete" method="POST">
                <input type="hidden" name="id" value="<?= $item['user_game']->id ?>">
                <button type="submit" class="btn" style="background:#5a2a2a;" onclick="return confirm('Удалить игру из коллекции?')">🗑 Удалить</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>В вашей коллекции пока нет игр. Перейдите в <a href="/src/catalog" style="color:#7c9eff;">каталог</a>, чтобы добавить их.</p>
<?php endif; ?>