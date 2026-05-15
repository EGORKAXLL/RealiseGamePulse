<h1><?= $game ? 'Редактировать игру' : 'Добавить игру' ?></h1>

<form method="POST" enctype="multipart/form-data" style="max-width:600px; margin-top:30px;">
    <div style="margin-bottom:15px;">
        <label>Название:</label>
        <input type="text" name="title" value="<?= $game ? htmlspecialchars($game->title) : '' ?>" required style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    </div>
    
    <div style="margin-bottom:15px;">
        <label>Разработчик:</label>
        <input type="text" name="developer" value="<?= $game ? htmlspecialchars($game->developer) : '' ?>" required style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    </div>
    
    <div style="margin-bottom:15px;">
        <label>Жанр:</label>
        <input type="text" name="genre" value="<?= $game ? htmlspecialchars($game->genre) : '' ?>" style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    </div>
    
    <div style="margin-bottom:15px;">
        <label>Описание:</label>
        <textarea name="description" rows="5" style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;"><?= $game ? htmlspecialchars($game->description) : '' ?></textarea>
    </div>
    
    <div style="margin-bottom:15px;">
        <label>Год выпуска:</label>
        <input type="number" name="release_year" value="<?= $game ? $game->release_year : '' ?>" style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    </div>
    
    <div style="margin-bottom:15px;">
        <label>Обложка:</label>
        <input type="file" name="cover_image" accept="image/*" style="width:100%; padding:10px; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
    </div>
    
    <button type="submit" class="btn">Сохранить</button>
    <a href="/src/admin/games" class="btn" style="background:#5a2a2a;">Отмена</a>
</form>