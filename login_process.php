<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// CSRFトークンを検証し、正しくない場合は処理を中断
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'], $token)) {
    $_SESSION['flash_error'] = '不正なリクエストです。';
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['flash_error'] = 'メールアドレスとパスワードを入力してください。';
    header('Location: login.php');
    exit;
}

try {
    $pdo = getPdo();

    // メールアドレスでユーザーを取得
    $stmt = $pdo->prepare('SELECT id, email, password_hash, display_name FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // ユーザー存在確認 + パスワード検証
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['flash_error'] = 'メールアドレスまたはパスワードが違います。';
        header('Location: login.php');
        exit;
    }

    // ログイン成功時はセッションIDを再生成して固定化攻撃を防ぐ
    session_regenerate_id(true);

    // マイページで利用する最低限のユーザー情報をセッションへ保存
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['display_name'] = $user['display_name'];

    $_SESSION['flash_success'] = 'ログインしました。';
    header('Location: mypage.php');
    exit;
} catch (Throwable $e) {
    $_SESSION['flash_error'] = 'ログイン処理中にエラーが発生しました。';
    header('Location: login.php');
    exit;
}
