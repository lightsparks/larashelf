# Larashelf

Monorepo:
- `src/larashelf.api`    — Laravel API (owns DB + migrations + auth)
- `src/larashelf.cms`    — Laravel CMS (UI, calls API)
- `src/larashelf.portal` — Laravel Portal (UI, calls API)

Services:
- MySQL (Docker)

## Prereqs
- WSL2 (Ubuntu), PHP 8.4 + extensions, Composer, Node LTS
- Docker Desktop with WSL integration enabled

---

## 1) Start database
bash:
```bash
cd ~/code/larashelf
docker compose up -d
```

## 2) Create local envs + keys
bash:
```bash
cd src/larashelf.api    && cp .env.example .env && php artisan key:generate && cd -
cd src/larashelf.cms     && cp .env.example .env && php artisan key:generate && cd -
cd src/larashelf.portal  && cp .env.example .env && php artisan key:generate && cd -
```

## 3) Migrate (API only)
bash:
```bash
cd src/larashelf.api
php artisan migrate
```

## 4) Run dev servers (three terminals)
bash:
```bash
cd src/larashelf.api    && php artisan serve --host=127.0.0.1 --port=8001
cd src/larashelf.cms     && php artisan serve --host=127.0.0.1 --port=8002
cd src/larashelf.portal  && php artisan serve --host=127.0.0.1 --port=8003
```