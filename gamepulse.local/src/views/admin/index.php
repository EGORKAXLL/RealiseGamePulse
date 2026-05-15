<h1>Админ-панель</h1>

<div style="display:flex; gap:20px; flex-wrap:wrap; margin-top:30px;">
    <div style="background:#141b2b; border-radius:20px; padding:25px; width:200px; text-align:center;">
        <div style="font-size:48px;">🎮</div>
        <div style="font-size:32px; font-weight:bold;"><?= $stats['games'] ?></div>
        <div>Игр</div>
        <a href="/src/admin/games" class="btn" style="margin-top:15px;">Управлять</a>
    </div>
    
    <div style="background:#141b2b; border-radius:20px; padding:25px; width:200px; text-align:center;">
        <div style="font-size:48px;">👥</div>
        <div style="font-size:32px; font-weight:bold;"><?= $stats['users'] ?></div>
        <div>Пользователей</div>
        <a href="/src/admin/users" class="btn" style="margin-top:15px;">Управлять</a>
    </div>
    
    <div style="background:#141b2b; border-radius:20px; padding:25px; width:200px; text-align:center;">
        <div style="font-size:48px;">📝</div>
        <div style="font-size:32px; font-weight:bold;"><?= $stats['reviews'] ?></div>
        <div>Отзывов</div>
        <a href="/src/admin/reviews" class="btn" style="margin-top:15px;">Модерировать</a>
    </div>
</div>