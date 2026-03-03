<?php
require_once __DIR__ . '/session.php';

// セッション変数を空にする
$_SESSION = [];

// セッションクッキーも無効化する
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'] ?? '',
        $params['secure'],
        $params['httponly']
    );
}

// サーバー側のセッション情報を破棄
session_destroy();

// ログアウト後はログイン画面へ
session_start();
$_SESSION['flash_success'] = 'ログアウトしました。';
header('Location: login.php');
exit;
