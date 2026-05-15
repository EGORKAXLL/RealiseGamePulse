<div style="max-width:400px; margin:0 auto; background:#141b2b; border-radius:20px; padding:40px;">
    <h1 style="text-align:center; margin-bottom:30px;">Регистрация</h1>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Логин (3-20 символов)" style="width:100%; padding:12px; margin:10px 0; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
        <input type="email" name="email" placeholder="Email" style="width:100%; padding:12px; margin:10px 0; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
        <input type="password" name="password" placeholder="Пароль (мин. 6 символов)" style="width:100%; padding:12px; margin:10px 0; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
        <input type="password" name="confirm_password" placeholder="Повторите пароль" style="width:100%; padding:12px; margin:10px 0; background:#1e263b; border:1px solid #36405e; border-radius:8px; color:white;">
        <button type="submit" class="btn" style="width:100%; margin-top:20px;">Зарегистрироваться</button>
    </form>
    <div style="text-align:center; margin-top:20px;">
        <a href="/src/login" style="color:#7c9eff;">Уже есть аккаунт? Войти</a>
    </div>
</div>