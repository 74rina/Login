# PHPログインサンプル（フレームワークなし）

教育用に、最小構成で以下を実装しています。
- ログイン画面 (`login.php`)
- マイページ (`mypage.php`)
- SQLiteデータベース作成 (`init_db.php`)
- セッション管理（`session.php`, `auth.php`）

## 1. 初期化

```bash
php init_db.php
```

サンプルユーザーが作成されます。
- email: `user@example.com`
- password: `password123`

## 2. 開発サーバー起動

```bash
php -S localhost:8000
```

ブラウザで以下にアクセスしてください。
- [http://localhost:8000/login.php](http://localhost:8000/login.php)

## セキュリティ実装の要点

- `password_hash()` / `password_verify()` でパスワードを安全に扱う
- `session_regenerate_id(true)` でセッション固定化対策
- CSRFトークンをログインフォームで検証
- 未ログインユーザーは `auth.php` でアクセス制御

