# GND_Scan — Ганимед Explorer

Публичный **блокчейн-сканер** экосистемы NEKSUS (GND, GANI). Laravel 12 + Breeze (auth) + Tailwind.

## Возможности

- **Главная:** поиск (адрес, хэш tx, номер блока), последние блоки и транзакции, сетевые метрики, ссылки на токены GND/GANI.
- **Блок:** `/block/{number}` — высота, время, хэш, валидатор, список транзакций.
- **Транзакция:** `/tx/{hash}` — статус, от/кому, сумма, комиссия, nonce, тип, input data.
- **Адрес:** `/address/{address}` — балансы (GND, GANI, токены), последние транзакции.
- **Контракт:** `/contract/{address}` — имя, стандарт, верификация, view-функции.
- **Токен:** `/token/{address}` — name, symbol, decimals, total supply, балансы.
- **Статистика:** `/stats` — метрики сети, транзакции, комиссии (из API ноды).
- **Валидаторы:** `/validators` — заглушка (ожидается API на ноде).

Данные загружаются с ноды GND по REST API (порт 8182). В `.env` задаётся `GND_NODE_URL`.

## Требования

- PHP 8.2+
- Composer
- Node.js & npm

## Установка

```bash
cd GND_Scan
composer install
cp .env.example .env
php artisan key:generate
# В .env задать GND_NODE_URL=http://localhost:8182 (или URL вашей ноды)
php artisan migrate
npm install && npm run build
```

## Запуск

```bash
php artisan serve
```

Откройте http://127.0.0.1:8000 — главная сканера.

## Конфигурация (.env)

| Переменная | Описание |
|------------|----------|
| `GND_NODE_URL` | URL ноды GND (например `http://localhost:8182`). Обязателен для работы сканера. |
| `GND_CONTRACT_ADDRESS` | (Опционально) Адрес контракта GND для ссылки «GND Token» на главной. |
| `GANI_CONTRACT_ADDRESS` | (Опционально) Адрес контракта GANI для ссылки «GANI Token» на главной. |

## Аутентификация (Breeze)

- **Вход / Регистрация:** `/login`, `/register`
- **Личный кабинет:** `/dashboard` (после входа)
- **Профиль:** `/profile`

БД по умолчанию — SQLite (`database/database.sqlite`).
