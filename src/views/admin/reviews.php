<h1>Модерация отзывов</h1>

<table style="width:100%; background:#141b2b; border-radius:16px; overflow:hidden;">
    <thead style="background:#1e263b;">
        <tr><th>Игра</th><th>Пользователь</th><th>Оценка</th><th>Отзыв</th><th>Дата</th><th>Действия</th></tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr style="border-bottom:1px solid #2a334c;">
                <td style="padding:12px;"><?= htmlspecialchars($review['title']) ?></td>
                <td><?= htmlspecialchars($review['username']) ?></td>
                <td style="color:#ffc107;"><?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?></td>
                <td style="max-width:300px;"><?= htmlspecialchars(substr($review['review_text'], 0, 100)) ?>...</td>
                <td><?= $review['created_at'] ?></td>
                <td>
                    <a href="/src/admin/reviews/delete?id=<?= $review['id'] ?>" class="btn" style="background:#5a2a2a; padding:4px 12px;" onclick="return confirm('Удалить отзыв?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>