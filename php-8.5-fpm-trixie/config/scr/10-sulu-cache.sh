#!/bin/bash
# Сбрасывает и прогревает кэш Sulu для ядер admin и website.
# Если консоли ещё нет — шаг пропускается.

clear_kernel() {
  local console=$1 label=$2
  if [[ ! -f "$APP_PATH/bin/$console" ]]; then
    log warning "bin/$console не найден — кэш $label пропускаю"
    return 0
  fi

  if php "bin/$console" cache:clear --no-interaction \
     && php "bin/$console" cache:warmup --no-interaction; then
    log success "Кэш $label очищен"
  else
    log warning "Не удалось очистить кэш $label"
  fi
}

if [[ ! -f "$APP_PATH/bin/adminconsole" ]]; then
  log warning "bin/adminconsole не найден — очистку кэша пропускаю"
else
  log info "Очищаю кэш Sulu (admin и website)…"
  clear_kernel adminconsole admin
  clear_kernel websiteconsole website
fi
