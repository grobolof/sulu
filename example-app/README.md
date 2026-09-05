# Быстрый старт 🚀

- ⬇️ Скачайте [example-app](.) (переименуйте раздел под ваш проект).
- 📄 Скопируйте `.env.example` в `.env` (см. [таблицу с переменными](#env-vars)). СУБД проекта — PostgreSQL.
- ⚡ Запустите проект выполнив команду из корня проекта: `make up`.
- 📊 В логах контейнера `application` вам будет доступен процесс создания проекта.
- ✅ Завершением сборки можно считать появление строки `✅ КОНТЕЙНЕР ГОТОВ — ЗАПУСКАЮ NGINX И PHP-FPM`.

⚠️ **КОРЕНЬ ПРОЕКТА** означает файлы приложения в папке `app`, которые будут смонтированы в контейнер!

<a id="env-vars"></a>

| Название переменной | Описание переменной | Требуется |
| :------------------ | :------------------ | :-------- |
| <a id="APP_HOST"></a>[APP_HOST](#APP_HOST) | Хост вашего проекта (хост необходимо добавить в файл hosts вашей системы, пример: `127.0.0.1   sulu.docker.local`) | ✅ |
| <a id="APP_PATH"></a>[APP_PATH](#APP_PATH) | Путь от корня до проекта внутри контейнера | ✅ |
| <a id="DB_CONNECTION"></a>[DB_CONNECTION](#DB_CONNECTION) | Драйвер Doctrine. Для этого примера — `pgsql`. Допустимо: `pgsql`, `mysql`, `mariadb`, `sqlite` | ✅ |
| <a id="DB_HOST"></a>[DB_HOST](#DB_HOST) | Хост базы данных (хостом БД является название контейнера СУБД из docker-compose.yml; используется для СУБД и Doctrine) | ✅ |
| <a id="DB_PORT"></a>[DB_PORT](#DB_PORT) | Порт базы данных. Для PostgreSQL — `5432` | ✅ |
| <a id="DB_DATABASE"></a>[DB_DATABASE](#DB_DATABASE) | Название базы данных (используется для СУБД и Doctrine) | ✅ |
| <a id="DB_USERNAME"></a>[DB_USERNAME](#DB_USERNAME) | Имя пользователя для базы данных (используется для СУБД и Doctrine) | ✅ |
| <a id="DB_PASSWORD"></a>[DB_PASSWORD](#DB_PASSWORD) | Пароль пользователя для базы данных (используется для СУБД и Doctrine) | ✅ |
| <a id="SYMFONY_CRON_ENABLED"></a>[SYMFONY_CRON_ENABLED](#SYMFONY_CRON_ENABLED) | Вкл/выкл CRON (1 - вкл.; раз в минуту): `php bin/adminconsole messenger:consume scheduler_default`. Допустимы только `0` или `1` | ✅ |
| <a id="MAILPIT_ENABLED"></a>[MAILPIT_ENABLED](#MAILPIT_ENABLED) | Вкл/выкл mailpit (1 - вкл.). Если задана — только `0` или `1`; любое другое значение — ошибка при старте контейнера | ❌ |
| <a id="MAILPIT_HOST"></a>[MAILPIT_HOST](#MAILPIT_HOST) | Хост mailpit. Обязательна, если [`MAILPIT_ENABLED`](#MAILPIT_ENABLED)=1 | ❌ |

## Установка Sulu

После успешной сборки проекта:

- Сайт: `http://APP_HOST` (заменить [**APP_HOST**](#APP_HOST) на хост из `.env` файла).
- Админка: `http://APP_HOST/admin`. Логин и пароль по умолчанию: **admin** / **admin**.

1. Если в каталоге `app` ещё не было проекта, контейнер сам создаст актуальную версию Sulu через `composer create-project sulu/skeleton`.
2. При первом создании проекта в файл `app/.env` записываются `DATABASE_URL`, `DEFAULT_URI` и `SULU_ADMIN_EMAIL` из доступов к СУБД и [APP_HOST](#APP_HOST).
3. При первом старте выполняется `php bin/adminconsole sulu:build dev` (схема, фикстуры, пользователь admin). На следующих стартах накатываются миграции Doctrine.
4. Для работы с приложением используйте `php bin/adminconsole` (админка) и `php bin/websiteconsole` (сайт) — см. [команды](#команды), `make console` и `make websiteconsole`.
5. 🔥 **Sulu** успешно установлен!

# Дополнительные настройки 🛠️

Дополнительные настройки являются рекомендованными, но необязательными и не препятствуют успешной работе проекта.

## Доступы СУБД для Sulu через переменные окружения

👇 При первом создании проекта значение уже подставляется в `app/.env` как `DATABASE_URL`. Если создаёте проект вручную, замените в `app/.env` переменную `DATABASE_URL` (из [DB_CONNECTION](#DB_CONNECTION), [DB_HOST](#DB_HOST), [DB_PORT](#DB_PORT), [DB_DATABASE](#DB_DATABASE), [DB_USERNAME](#DB_USERNAME), [DB_PASSWORD](#DB_PASSWORD)).

```
DATABASE_URL="postgresql://login:pass@database:5432/sulu?serverVersion=18&charset=utf8"
```

Также проверьте:

```
DEFAULT_URI=http://sulu.docker.local
SULU_ADMIN_EMAIL=admin@sulu.docker.local
```

## Настройка почты через Mailpit

👇 Если [`MAILPIT_ENABLED`](#MAILPIT_ENABLED)=1, контейнер направит `mail()` PHP в Mailpit и пропишет `MAILER_DSN` в `app/.env`. Письма смотрите на `http://localhost:8025`.

## Русская локализация для админ-панели

Из коробки интерфейс админки Sulu доступен на английском и немецком. Русский перевод можно подключить отдельно: его скачивают с [Crowdin](https://sulu.crowdin.com/sulusulu) и включают в конфиге проекта.

Это язык **интерфейса админки** (меню, кнопки, формы). Язык контента сайта настраивается отдельно в webspace (`config/webspaces/`).

1. В `app/config/packages/sulu_admin.yaml` добавьте локаль `ru`:

```yaml
sulu_core:
    locales:
        en: English
        de: Deutsch
        ru: Русский
    translations:
        - en
        - de
        - ru
```

2. Скачайте перевод:

```bash
php bin/adminconsole sulu:admin:download-language ru
```

3. Очистите кеш админки и в профиле пользователя выберите русский язык.

Если языка `ru` ещё нет на Crowdin, команда не скачает файлы, а интерфейс останется на английском (fallback). Тогда можно [попросить команду Sulu](https://sulu.io/contact-us) завести язык в проекте переводов или положить свои файлы `admin.ru.json` в `translations/` проекта.

Подробнее: [Getting Started](https://docs.sulu.io/en/latest/book/getting-started.html) (секция про языки админки) и список языков на [Crowdin](https://sulu.crowdin.com/sulusulu).

# Документация, пакеты и команды 🎨

В данном пункте представлены полезные ссылки на документацию и пакеты для более комфортной разработки.

## Документация

1. [Getting Started](https://docs.sulu.io/en/latest/book/getting-started.html) — Создание проекта и первый вход в админку.
2. [Webspaces](https://docs.sulu.io/en/latest/book/webspaces.html) — Мультисайтовость и языки.
3. [Templates](https://docs.sulu.io/en/latest/book/templates.html) — Шаблоны страниц.
4. [Doctrine](https://symfony.com/doc/current/doctrine.html) — Работа с базой данных (Sulu построен на Symfony).
5. [Console](https://docs.sulu.io/en/latest/book/getting-started.html) — `bin/adminconsole` и `bin/websiteconsole`.

## Пакеты

В [sulu/skeleton](https://github.com/sulu/skeleton) уже входят phpunit, php-cs-fixer, phpstan и rector. Примеры конфигов ниже — если хотите заменить стандартные.

### Тестирование и рефакторинг

1. [phpunit](https://github.com/sebastianbergmann/phpunit) — Официальный тестовый раннер PHP (есть в проекте Sulu по умолчанию).
2. [rector](https://github.com/rectorphp/rector) — Автоматический рефакторинг PHP: обновляет синтаксис и упрощает конструкции. Пример [конфига](attachments/packages/rector/rector.php) (скопировать в корень проекта).
3. [php-cs-fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) — Форматирует код по PSR-12 и заданным правилам стиля. Пример [конфига](attachments/packages/php-cs-fixer/.php-cs-fixer.dist.php) (скопировать в корень проекта).
4. [phpstan](https://github.com/phpstan/phpstan) — Статический анализ: находит ошибки типов и логики без запуска кода. Пример [конфига](attachments/packages/phpstan/phpstan.neon) (скопировать в корень проекта).

### Lefthook (git)

**Lefthook** — это инструмент для управления Git-хуками.

- Установить [Node.js](https://nodejs.org/en/download)
- В корне проекта запустить команду, которая установит пакет `lefthook`

```bash
npm install lefthook --save-dev
```

- В корне проекта запустить команду, которая настроит `git hooks` из файла `lefthook.yml`

```bash
node_modules/.bin/lefthook install
```

- Залить изменения в ваш Git репозиторий
- Файл `lefthook.yml` изменить следующим образом (при каждом `git push` автоматически проверяет и рефакторит код):

```yaml
pre-commit:
  commands:
    # ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ PHP-CS-FIXER:
    phpcs:
      priority: 1
      run: make phpcs && git add {staged_files}

    # ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ PHPSTAN:
    phpstan:
      priority: 2
      run: make phpstan

    # ПРИ УСЛОВИИ ЧТО УСТАНОВЛЕН ПАКЕТ RECTOR:
    rector:
      priority: 3
      run: make rector && git add {staged_files}
```

## Команды

### Загрузить БД в контейнер (postgres)

```bash
docker exec -i $(basename $(pwd))-database-1 sh -c 'psql -U "$POSTGRES_USER" -d "$POSTGRES_DB"' < ./docker/postgres/db.sql
```

### Выгрузить БД из контейнера (postgres)

```bash
docker exec $(basename $(pwd))-database-1 sh -c 'pg_dump -U "$POSTGRES_USER" -d "$POSTGRES_DB"' > ./docker/postgres/db.sql
```
