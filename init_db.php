<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = getPdo();

    // usersテーブルを作成（存在しなければ）
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            display_name TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
    );

    // 教育用サンプルユーザーを1件投入
    // メール: user@example.com
    // パスワード: password123
    $email = 'user@example.com';
    $password = 'password123';
    $displayName = 'サンプルユーザー';

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $exists = $stmt->fetch();

    if (!$exists) {
        // パスワードは必ずハッシュ化して保存する（平文保存はNG）
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $insert = $pdo->prepare(
            'INSERT INTO users (email, password_hash, display_name, created_at)
             VALUES (:email, :password_hash, :display_name, :created_at)'
        );
        $insert->execute([
            ':email' => $email,
            ':password_hash' => $hash,
            ':display_name' => $displayName,
            ':created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    echo "DB初期化が完了しました。\n";
    echo "ログイン情報: user@example.com / password123\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'DB初期化中にエラーが発生しました: ' . $e->getMessage() . "\n";
    exit(1);
}
