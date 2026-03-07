<?php
// .env ファイルを読み込み、KEY=VALUE を環境変数へ反映する
function loadDotEnv(string $path): void
{
    static $loaded = false;
    if ($loaded || !is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $key = trim($parts[0]);
        $value = trim($parts[1]);

        // "value" や 'value' のクォートを外す
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    $loaded = true;
}

// 環境変数を読み取り、未設定時はデフォルト値を返すヘルパー
function envOrDefault(string $key, string $default): string
{
    loadDotEnv(__DIR__ . '/.env');

    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }
    return $value;
}

// 例外ベースでPDOエラーを扱うための設定をまとめた関数
function getPdo(): PDO
{
    // 教育用サンプルとして、接続情報は .env（または環境変数）から取得する
    // DB_DRIVER: sqlite のみ対応（将来は mysql などを追加可能）
    // DB_PATH  : SQLiteファイルの絶対/相対パス
    $driver = envOrDefault('DB_DRIVER', 'sqlite');
    $dbPath = envOrDefault('DB_PATH', __DIR__ . '/data/app.sqlite');

    if ($driver !== 'sqlite') {
        throw new RuntimeException('現在サポートしているDB_DRIVERは sqlite のみです。');
    }

    // SQLiteファイル保存ディレクトリが無ければ作成
    $dataDir = dirname($dbPath);
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
}
