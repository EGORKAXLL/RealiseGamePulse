<h1>👥 Друзья</h1>

<!-- Рекомендованные друзья -->
<?php if (count($recommendedFriends) > 0): ?>
    <div class="section">
        <h2 class="section-title">🎮 Рекомендованные друзья (похожие игры)</h2>
        <?php foreach ($recommendedFriends as $rec): ?>
            <div style="background:#1e263b; border-radius:12px; padding:15px 20px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div style="width:45px; height:45px; background:#3a4565; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px;">👤</div>
                    <div>
                        <div style="font-weight:bold;"><?= htmlspecialchars($rec['user']->username) ?> <span style="background:#2a3457; padding:2px 8px; border-radius:20px; font-size:11px; margin-left:10px;"><?= $rec['common_games'] ?> общих игр</span></div>
                        <div style="font-size:12px; color:#8e9fc7;"><?= htmlspecialchars($rec['user']->email) ?></div>
                        <div style="font-size:12px; color:#ffc107; margin-top:5px;">
                            🎮 Играет в: <?php $titles = []; foreach ($rec['games'] as $g) $titles[] = htmlspecialchars($g['title']); echo implode(', ', $titles); ?>
                        </div>
                    </div>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="friend_id" value="<?= $rec['user']->id ?>">
                    <button type="submit" class="btn" style="background:#2a5a7a;">➕ Добавить в друзья</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Входящие заявки -->
<?php if (count($incomingList) > 0): ?>
    <div class="section">
        <h2 class="section-title">📨 Входящие заявки</h2>
        <?php foreach ($incomingList as $item): ?>
            <div style="background:#1e263b; border-radius:12px; padding:15px 20px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div style="width:45px; height:45px; background:#3a4565; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px;">👤</div>
                    <div>
                        <div style="font-weight:bold;"><?= htmlspecialchars($item['sender']->username) ?></div>
                        <div style="font-size:12px; color:#8e9fc7;"><?= htmlspecialchars($item['sender']->email) ?></div>
                    </div>
                </div>
                <div>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="accept">
                        <input type="hidden" name="friend_id" value="<?= $item['request']->id ?>">
                        <button type="submit" class="btn" style="background:#2a5a2a;">✅ Принять</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="reject">
                        <input type="hidden" name="friend_id" value="<?= $item['request']->id ?>">
                        <button type="submit" class="btn" style="background:#5a2a2a;">❌ Отклонить</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Мои друзья -->
<div class="section">
    <h2 class="section-title">🌟 Мои друзья (<?= count($friendsList) ?>)</h2>
    <?php if (count($friendsList) > 0): ?>
        <?php foreach ($friendsList as $item): ?>
            <div style="background:#1e263b; border-radius:12px; padding:15px 20px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div style="width:45px; height:45px; background:#3a4565; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px;">👤</div>
                    <div>
                        <div style="font-weight:bold;"><?= htmlspecialchars($item['friend']->username) ?></div>
                        <div style="font-size:12px; color:#8e9fc7;"><?= htmlspecialchars($item['friend']->email) ?></div>
                    </div>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="friend_id" value="<?= $item['relation']->id ?>">
                    <button type="submit" class="btn" style="background:#5a2a2a;" onclick="return confirm('Удалить из друзей?')">🗑 Удалить</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>У вас пока нет друзей. Добавьте рекомендованных выше!</p>
    <?php endif; ?>
</div>

<!-- Поиск пользователей -->
<div class="section">
    <h2 class="section-title">🔍 Найти пользователей</h2>
    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" placeholder="Введите имя пользователя..." value="<?= htmlspecialchars($search) ?>" style="padding:10px; width:250px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
        <button type="submit" class="btn" style="margin-left:10px;">Найти</button>
        <?php if ($search): ?>
            <a href="/src/friends" style="color:#7c9eff; margin-left:10px;">Сбросить</a>
        <?php endif; ?>
    </form>

    <?php if ($search): ?>
        <?php if (count($searchResults) > 0): ?>
            <?php foreach ($searchResults as $user): ?>
                <?php 
                    $isFriend = false;
                    $hasPending = false;
                    foreach ($friendsList as $f) { if ($f['friend']->id == $user->id) $isFriend = true; }
                    $req = \Models\Friend::getRequests($pdo, $currentUser->id);
                    foreach ($req as $r) {
                        if (($r->gamer1_id == $currentUser->id && $r->gamer2_id == $user->id)) $hasPending = true;
                    }
                ?>
                <div style="background:#1e263b; border-radius:12px; padding:15px 20px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div style="width:45px; height:45px; background:#3a4565; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px;">👤</div>
                        <div>
                            <div style="font-weight:bold;"><?= htmlspecialchars($user->username) ?></div>
                            <div style="font-size:12px; color:#8e9fc7;"><?= htmlspecialchars($user->email) ?></div>
                        </div>
                    </div>
                    <div>
                        <?php if ($isFriend): ?>
                            <span style="color:#7cff9e;">✓ Уже в друзьях</span>
                        <?php elseif ($hasPending): ?>
                            <span style="color:#ffc107;">⏳ Заявка отправлена</span>
                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="friend_id" value="<?= $user->id ?>">
                                <button type="submit" class="btn" style="background:#2a5a7a;">➕ Добавить в друзья</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Пользователи не найдены</p>
        <?php endif; ?>
    <?php endif; ?>
</div>