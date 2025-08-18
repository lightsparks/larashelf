# Larashelf API

Laravel API that owns the database, migrations, and authentication.

> See the [root README](../../README.md) for prerequisites and Docker setup.

## Quick start (local)
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8001
