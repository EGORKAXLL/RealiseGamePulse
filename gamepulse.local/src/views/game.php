<div class="section" style="margin-bottom:30px;">
    <h1 class="game-title" style="font-size:32px; margin-bottom:15px;"><?= htmlspecialchars($game->title) ?></h1>
    <div class="game-meta" style="margin-bottom:20px;">
        Разработчик: <?= htmlspecialchars($game->developer) ?><br>
        Жанр: <?= htmlspecialchars($game->genre) ?><br>
        Год выпуска: <?= $game->release_year ?>
    </div>
    <div class="stars" style="font-size:24px; color:#ffc107; margin-bottom:20px;">
        Рейтинг: <?= $avgRating ?> / 5 (<?= count($reviews) ?> отзывов)
    </div>
    <div class="description" style="line-height:1.6;">
        <strong>Описание:</strong><br>
        <?= nl2br(htmlspecialchars($game->description ?? 'Описание отсутствует')) ?>
    </div>
</div>

<?php if ($currentUser && !$userGame): ?>
    <div class="section">
        <h2 class="section-title">➕ Добавить в коллекцию</h2>
        <form action="/src/collection/add" method="POST">
            <input type="hidden" name="game_id" value="<?= $game->id ?>">
            <div style="margin-bottom:15px;">
                <label>Статус:</label>
                <select name="status" style="margin-left:10px; padding:5px; background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white;">
                    <option value="planned">В планах</option>
                    <option value="playing">Играю</option>
                    <option value="completed">Пройдено</option>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label>Оценка (1-5):</label>
                <select name="rating" style="margin-left:10px; padding:5px; background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white;">
                    <option value="">Не оценено</option>
                    <option value="1">1 ★</option>
                    <option value="2">2 ★★</option>
                    <option value="3">3 ★★★</option>
                    <option value="4">4 ★★★★</option>
                    <option value="5">5 ★★★★★</option>
                </select>
            </div>
            <button type="submit" class="btn">Добавить</button>
        </form>
    </div>
<?php endif; ?>

<div class="section">
    <h2 class="section-title">📝 Отзывы</h2>
    <?php if (count($reviews) > 0): ?>
        <?php foreach ($reviews as $review): ?>
            <div style="background:#1e263b; border-radius:12px; padding:15px; margin-bottom:15px;">
                <div style="color:#7c9eff; margin-bottom:10px;">
                    <?php 
                        $author = new Models\Gamer($pdo);
                        $author->load($review->gamer_id);
                        echo htmlspecialchars($author->username);
                    ?>
                </div>
                <div style="color:#ffc107; margin-bottom:10px;">
                    <?= str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) ?>
                </div>
                <div style="line-height:1.5;">
                    <?= nl2br(htmlspecialchars($review->review_text)) ?>
                </div>
                <?php if ($currentUser && ($review->gamer_id == $currentUser->id || $currentUser->role == 'admin')): ?>
                    <form action="/src/review/delete" method="POST" style="margin-top:10px;">
                        <input type="hidden" name="id" value="<?= $review->id ?>">
                        <button type="submit" class="btn" style="background:#5a2a2a;" onclick="return confirm('Удалить отзыв?')">🗑 Удалить</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Пока нет отзывов. Будьте первым!</p>
    <?php endif; ?>
</div>

<?php if ($currentUser && !$userReview): ?>
    <div class="section">
        <h2 class="section-title">✍️ Написать отзыв</h2>
        <form action="/src/review/add" method="POST">
            <input type="hidden" name="game_id" value="<?= $game->id ?>">
            <div style="margin-bottom:15px;">
                <label>Оценка:</label>
                <select name="rating" required style="margin-left:10px; padding:5px; background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white;">
                    <option value="1">1 ★</option>
                    <option value="2">2 ★★</option>
                    <option value="3">3 ★★★</option>
                    <option value="4">4 ★★★★</option>
                    <option value="5">5 ★★★★★</option>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label>Текст отзыва:</label>
                <textarea name="review_text" required style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white; min-height:100px;"></textarea>
            </div>
            <button type="submit" class="btn">Отправить отзыв</button>
        </form>
    </div>
<?php endif; ?>