<?php
// DBファイルの保存先を定義
const DB_FILE = __DIR__ . '/data/app.sqlite';

// 例外ベースでPDOエラーを扱うための設定をまとめた関数
function getPdo(): PDO
{
    // dataディレクトリが無ければ作成
    $dataDir = __DIR__ . '/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
}
