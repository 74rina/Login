<?php
// セッションの安全性を上げるため、Cookie属性やstrict modeを設定してから開始
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, // HTTPS環境なら true に変更
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// CSRFトークンを未作成なら生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
