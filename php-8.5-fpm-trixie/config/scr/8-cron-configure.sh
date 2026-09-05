#!/bin/bash
# Вешает поминутный запуск messenger:consume scheduler_default, если SYMFONY_CRON_ENABLED=1.
# Без крона планировщик Symfony (компонент Scheduler) в контейнере Sulu не выполняется.

if [[ $SYMFONY_CRON_ENABLED != 1 ]]; then
  log warning "Крон выключен — планировщик Symfony запускаться не будет"
else
  log info "Включаю планировщик Symfony: messenger:consume scheduler_default каждую минуту…"
  { env; echo "*/1 * * * * cd ${APP_PATH} && /usr/local/bin/php bin/console messenger:consume scheduler_default --time-limit=55 --quiet >> /dev/null 2>&1"; } | crontab -
  log success "Крон настроен"
fi
