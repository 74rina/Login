<?php
require_once __DIR__ . '/session.php';

// 認証必須ページで使う共通ガード
function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash_error'] = 'ログインが必要です。';
        header('Location: login.php');
        exit;
    }
}
