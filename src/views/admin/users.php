<h1>Управление пользователями</h1>

<table style="width:100%; background:#141b2b; border-radius:16px; overflow:hidden;">
    <thead style="background:#1e263b;">
        <tr><th style="padding:12px;">ID</th><th>Логин</th><th>Email</th><th>Роль</th><th>Дата</th><th>Действия</th></tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr style="border-bottom:1px solid #2a334c;">
                <td style="padding:12px;"><?= $user->id ?></td>
                <td><?= htmlspecialchars($user->username) ?></td>
                <td><?= htmlspecialchars($user->email) ?></td>
                <td>
                    <form method="POST" action="/src/admin/users/role?id=<?= $user->id ?>" style="display:inline;">
                        <select name="role" onchange="this.form.submit()" style="background:#1e263b; border:1px solid #36405e; border-radius:6px; color:white; padding:4px;">
                            <option value="gamer" <?= $user->role == 'gamer' ? 'selected' : '' ?>>Геймер</option>
                            <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Админ</option>
                        </select>
                    </form>
                </td>
                <td><?= $user->created_at ?></td>
                <td>
                    <?php if ($user->id != $currentUser->id): ?>
                        <a href="/src/admin/users/delete?id=<?= $user->id ?>" class="btn" style="background:#5a2a2a; padding:4px 12px;" onclick="return confirm('Удалить пользователя?')">🗑️</a>
                    <?php else: ?>
                        <span style="color:#6a7aa3;">(Вы)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>