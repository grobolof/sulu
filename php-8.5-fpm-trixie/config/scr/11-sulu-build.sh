#!/bin/bash
# Первый запуск: таблицы Sulu (без сущностей App\), фикстуры, пользователь admin/admin,
# затем doctrine:migrations:migrate для таблиц приложения.
# Повторные запуски: doctrine:migrations:migrate.
# Если СУБД ещё не готова — шаг не роняет контейнер, только предупреждает.

adminconsole() {
  php bin/adminconsole "$@"
}

sulu_is_initialized() {
  adminconsole doctrine:query:dql \
    'SELECT u.id FROM Sulu\Bundle\SecurityBundle\Entity\User u' \
    --max-results=1 --no-interaction >/dev/null 2>&1
}

run_sulu_schema() {
  php /tmp/scr/sulu-schema-without-app.php
}

run_build() {
  # MassiveBuild спрашивает «Look good?»; UserBuilder — пароль админа.
  # Без --no-interaction, иначе QuestionHelper падает (у пароля нет default).
  # database-билдер sulu:build создаёт и таблицы приложения, поэтому схему
  # собирает sulu-schema-without-app.php, а остальные шаги идут по отдельности.
  adminconsole doctrine:database:create --if-not-exists --no-interaction || return 1
  run_sulu_schema || return 1

  local target
  for target in homepage fixtures user system_collections security; do
    printf 'y\nadmin\n' | timeout 600 php bin/adminconsole sulu:build "$target" --nodeps --keep-exit-code || return 1
  done
}

after_build() {
  log info "Накатываю миграции приложения…"
  if run_migrations; then
    log success "Миграции выполнены"
  else
    log warning "Миграции не выполнены — проверьте подключение к БД"
  fi
}

run_migrations() {
  adminconsole doctrine:migrations:migrate --no-interaction --allow-no-migration
}

if [[ ! -f "$APP_PATH/bin/adminconsole" ]]; then
  log warning "bin/adminconsole не найден — инициализацию Sulu пропускаю"
elif [[ $DB_CONNECTION == sqlite ]]; then
  if sulu_is_initialized; then
    log info "Sulu уже инициализирован — накатываю миграции (sqlite)…"
    if run_migrations; then
      log success "Миграции выполнены"
    else
      log warning "Миграции не выполнены — проверьте подключение к БД"
    fi
  else
    log info "Инициализирую Sulu (sqlite): схема Sulu, затем фикстуры…"
    if run_build; then
      log success "Sulu инициализирован (логин admin / пароль admin)"
      after_build
    else
      log warning "sulu:build не выполнен — проверьте подключение к БД"
    fi
  fi
else
  log info "Жду СУБД $DB_HOST:$DB_PORT…"
  if ! wait-for-it "${DB_HOST}:${DB_PORT}" -t 60; then
    log warning "СУБД не отвечает — инициализацию Sulu пропускаю"
  else
    ready=0
    for _ in 1 2 3 4 5 6 7 8 9 10; do
      if sulu_is_initialized; then
        ready=1
        break
      fi
      if adminconsole dbal:run-sql "SELECT 1" --no-interaction >/dev/null 2>&1; then
        ready=2
        break
      fi
      sleep 3
    done

    if [[ $ready == 1 ]]; then
      log info "Sulu уже инициализирован — накатываю миграции…"
      if run_migrations; then
        log success "Миграции выполнены"
      else
        log warning "Миграции не выполнены — проверьте подключение к БД"
      fi
    else
      log info "Инициализирую Sulu: схема Sulu, затем фикстуры…"
      built=0
      for _ in 1 2 3 4 5; do
        if run_build; then
          built=1
          break
        fi
        sleep 3
      done
      if [[ $built == 1 ]]; then
        log success "Sulu инициализирован (логин admin / пароль admin)"
        after_build
      else
        log warning "sulu:build не выполнен — проверьте подключение к БД"
      fi
    fi
  fi
fi
