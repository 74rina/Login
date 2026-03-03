<?php
require_once __DIR__ . '/auth.php';

// 未ログイン時は login.php へリダイレクト
requireLogin();

$displayName = $_SESSION['display_name'] ?? 'ユーザー';
$email = $_SESSION['user_email'] ?? '';
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>マイページ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="container">
    <h1>マイページ</h1>
    <p><strong>ようこそ、<?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?> さん</strong></p>
    <p>ログイン中のメールアドレス: <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></p>

    <div class="actions">
      <a class="btn-link" href="logout.php">ログアウト</a>
    </div>
  </main>
</body>
</html>
