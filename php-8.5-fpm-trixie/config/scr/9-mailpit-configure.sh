#!/bin/bash
# Перенаправляет PHP mail() в Mailpit, если MAILPIT_ENABLED=1.
# Письма из Sulu не уходят наружу, а открываются в веб-интерфейсе Mailpit.

ini=/usr/local/etc/php/conf.d/php.ini

set_env() {
  local key=$1 value=$2 file="$APP_PATH/.env"
  [[ -f "$file" ]] || return 0
  if grep -qE "^#?${key}=" "$file"; then
    sed -i "s|^#\?${key}=.*|${key}=${value}|" "$file"
  else
    printf '%s=%s\n' "$key" "$value" >> "$file"
  fi
}

if [[ ${MAILPIT_ENABLED:-0} != 1 ]]; then
  log warning "Mailpit выключен — письма пойдут через системный sendmail"
else
  if grep -q 'mailpit sendmail' "$ini"; then
    log success "Почта PHP уже направлена в Mailpit"
  else
    printf '\n[mail]\nsendmail_path = "/usr/local/bin/mailpit sendmail --smtp-addr %s"\n' "$MAILPIT_HOST" >> "$ini"
    log success "Письма PHP будут попадать в Mailpit ($MAILPIT_HOST)"
  fi

  mail_host="${MAILPIT_HOST%%:*}"
  mail_port="${MAILPIT_HOST##*:}"
  [[ "$mail_host" == "$mail_port" ]] && mail_port=1025

  set_env MAILER_DSN "\"smtp://${mail_host}:${mail_port}\""
fi
