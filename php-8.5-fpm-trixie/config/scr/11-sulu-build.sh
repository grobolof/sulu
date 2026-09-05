#!/bin/bash
# Первый запуск: sulu:build dev (схема, фикстуры, пользователь admin/admin).
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

run_build() {
  # MassiveBuild спрашивает «Look good?»; UserBuilder — пароль админа.
  # Без --no-interaction, иначе QuestionHelper падает (у пароля нет default).
  printf 'y\nadmin\n' | timeout 600 php bin/adminconsole sulu:build dev
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
    log info "Инициализирую Sulu (sqlite): sulu:build dev…"
    if run_build; then
      log success "Sulu инициализирован (логин admin / пароль admin)"
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
      log info "Инициализирую Sulu: sulu:build dev…"
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
      else
        log warning "sulu:build не выполнен — проверьте подключение к БД"
      fi
    fi
  fi
fi
