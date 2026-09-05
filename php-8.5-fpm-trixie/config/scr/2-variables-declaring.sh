#!/bin/bash
# Читает и проверяет переменные окружения контейнера.
# Без APP_PATH и APP_HOST Nginx не узнает, куда класть сайт и какой домен слушать.

require() {
  [[ -n "${!1}" ]] || { log error "Не задана переменная $1 — укажите её в .env"; exit 1; }
}

require_01() {
  local name=$1
  local value=${!name}
  [[ -n "$value" ]] || { log error "Не задана переменная $name — укажите 0 или 1 в .env"; exit 1; }
  case $value in
    0|1) ;;
    *)
      log error "$name=$value недопустима. Допустимо: 0 или 1"
      exit 1
      ;;
  esac
}

require APP_PATH
require APP_HOST
require DB_CONNECTION
require DB_HOST
require DB_PORT
require DB_DATABASE
require DB_USERNAME
require DB_PASSWORD
require_01 SYMFONY_CRON_ENABLED

case $DB_CONNECTION in
  pgsql|mysql|mariadb|sqlite) ;;
  *)
    log error "DB_CONNECTION=$DB_CONNECTION неизвестна. Допустимо: pgsql, mysql, mariadb, sqlite"
    exit 1
    ;;
esac

if [[ ${MAILPIT_ENABLED+set} == set ]]; then
  require_01 MAILPIT_ENABLED
  [[ $MAILPIT_ENABLED != 1 ]] || require MAILPIT_HOST
fi

log info "Сайт: $APP_PATH  ·  домен: $APP_HOST  ·  СУБД: $DB_CONNECTION"
mkdir -p "$APP_PATH"
cd "$APP_PATH"
