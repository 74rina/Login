<?php
require_once __DIR__ . '/session.php';

// ログイン済みならマイページへ遷移
if (!empty($_SESSION['user_id'])) {
    header('Location: mypage.php');
    exit;
}

$error = $_SESSION['flash_error'] ?? null;
$success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container">
    <h1>ログイン</h1>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- CSRF対策として hidden でトークンを送る -->
    <form action="login_process.php" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

      <div class="form-group">
        <label for="email">メールアドレス</label>
        <input id="email" type="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">パスワード</label>
        <input id="password" type="password" name="password" required>
      </div>

      <button type="submit">ログイン</button>
    </form>

    <p>初期ユーザー: user@example.com / password123</p>
  </main>
</body>
</html>
