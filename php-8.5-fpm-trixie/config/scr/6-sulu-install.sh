#!/bin/bash
# Создаёт проект Sulu в APP_PATH, если bin/adminconsole ещё нет.
# APP_PATH — корень репозитория (volume ./:${APP_PATH}), поэтому docker-compose.yml,
# makefile, README, .env и .gitignore не перезаписываются.

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

merge_env() {
  local src=$1 dest=$2
  [[ -f "$src" && -f "$dest" ]] || return 0
  local line key
  while IFS= read -r line || [[ -n "$line" ]]; do
    [[ "$line" =~ ^[[:space:]]*# ]] && continue
    [[ -z "${line// }" ]] && continue
    key="${line%%=*}"
    [[ -n "$key" ]] || continue
    if ! grep -qE "^#?${key}=" "$dest"; then
      printf '%s\n' "$line" >> "$dest"
    fi
  done < "$src"
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

  # -n: не затираем файлы репозитория, уже лежащие в корне (compose, .env, README)
  cp -an /tmp/sulu-app/. "$APP_PATH/"

  if [[ ! -f "$APP_PATH/.env" ]]; then
    if [[ -f /tmp/sulu-app/.env ]]; then
      cp /tmp/sulu-app/.env "$APP_PATH/.env"
    elif [[ -f "$APP_PATH/.env.example" ]]; then
      cp "$APP_PATH/.env.example" "$APP_PATH/.env"
    fi
  fi

  merge_env /tmp/sulu-app/.env "$APP_PATH/.env"
  rm -rf /tmp/sulu-app

  set_env DATABASE_URL "$(database_url)"
  set_env DEFAULT_URI "http://${APP_HOST}"
  set_env SULU_ADMIN_EMAIL "admin@${APP_HOST}"

  mkdir -p "$APP_PATH/var" "$APP_PATH/public/uploads"
  chmod -R ug+rwx "$APP_PATH/var" "$APP_PATH/public/uploads"
  log success "Проект Sulu создан в $APP_PATH"
fi
