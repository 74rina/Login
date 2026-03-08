<?php
// .env を組み込み関数で読み込み、連想配列として返す
function loadEnvFile(string $path): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    if (!is_file($path)) {
        $cache = [];
        return $cache;
    }

    $parsed = parse_ini_file($path, false, INI_SCANNER_RAW);
    $cache = is_array($parsed) ? $parsed : [];
    return $cache;
}

// 環境変数を読み取り、未設定時はデフォルト値を返すヘルパー
function envOrDefault(string $key, string $default): string
{
    // 1) 実行環境の環境変数 2) .env 3) デフォルト値 の順で採用
    $envValue = getenv($key);
    if ($envValue !== false && $envValue !== '') {
        return $envValue;
    }

    $fileValues = loadEnvFile(__DIR__ . '/.env');
    $fileValue = $fileValues[$key] ?? '';
    if ($fileValue !== '') {
        return (string)$fileValue;
    }

    return $default;
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
