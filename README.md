# Login Sample

## Docker

```bash
docker compose up --build
```

Open `http://localhost:8000/login.php`

Initial login:

- `user@example.com`
- `password123`

## VS Code Dev Container

1. Open this folder in VS Code.
2. Run `Dev Containers: Reopen in Container`.
3. Open `http://localhost:8000/login.php` from the forwarded port.

The container starts the PHP built-in server on port `8000` and initializes SQLite automatically.
