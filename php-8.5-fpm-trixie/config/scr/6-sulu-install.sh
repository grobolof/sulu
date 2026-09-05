#!/bin/bash
# Создаёт проект Sulu, если bin/adminconsole ещё нет в каталоге приложения.

set_env() {
  local key=$1 value=$2 file="$APP_PATH/.env"
  [[ -f "$file" ]] || return 0
  local escaped=${value//\\/\\\\}
  escaped=${escaped//&/\\&}
  if grep -qE "^#?${key}=" "$file"; then
    sed -i "s|^#\?${key}=.*|${key}=${escaped}|" "$file"
  else
    printf '%s=%s\n' "$key" "$value" >> "$file"
  fi
}

database_url() {
  case $DB_CONNECTION in
    pgsql)
      printf '"postgresql://%s:%s@%s:%s/%s?serverVersion=18&charset=utf8"' \
        "$DB_USERNAME" "$DB_PASSWORD" "$DB_HOST" "$DB_PORT" "$DB_DATABASE"
      ;;
    mysql)
      printf '"mysql://%s:%s@%s:%s/%s?serverVersion=8.0&charset=utf8mb4"' \
        "$DB_USERNAME" "$DB_PASSWORD" "$DB_HOST" "$DB_PORT" "$DB_DATABASE"
      ;;
    mariadb)
      printf '"mysql://%s:%s@%s:%s/%s?serverVersion=11&charset=utf8mb4"' \
        "$DB_USERNAME" "$DB_PASSWORD" "$DB_HOST" "$DB_PORT" "$DB_DATABASE"
      ;;
    sqlite)
      printf '"sqlite:///%s/var/data.db"' "$APP_PATH"
      ;;
  esac
}

if [[ -f "$APP_PATH/bin/adminconsole" ]]; then
  log success "Sulu уже есть в $APP_PATH — создавать не нужно"
else
  log info "Создаю проект Sulu (sulu/skeleton), это может занять несколько минут…"
  composer create-project sulu/skeleton /tmp/sulu-app --no-interaction --no-scripts
  cp -a /tmp/sulu-app/. "$APP_PATH/"
  rm -rf /tmp/sulu-app

  set_env DATABASE_URL "$(database_url)"
  set_env DEFAULT_URI "http://${APP_HOST}"
  set_env SULU_ADMIN_EMAIL "admin@${APP_HOST}"

  mkdir -p "$APP_PATH/var" "$APP_PATH/public/uploads"
  chmod -R ug+rwx "$APP_PATH/var" "$APP_PATH/public/uploads"
  log success "Проект Sulu создан в $APP_PATH"
fi
