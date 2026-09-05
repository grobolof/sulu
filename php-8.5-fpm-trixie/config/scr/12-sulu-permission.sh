#!/bin/bash
# Выставляет ACL на var/ и public/uploads/, чтобы PHP-FPM и CLI могли писать кэш, логи и медиа.
# Рекомендация Sulu: https://docs.sulu.io/en/latest/cookbook/web-server/nginx.html
# На bind-mount (macOS Docker Desktop) POSIX ACL часто недоступен — тогда chmod.

apply_writable() {
  local dir=$1
  mkdir -p "$dir"
  if setfacl -dR -m u:"$HTTPDUSER":rwX -m u:"$(whoami)":rwX "$dir" 2>/dev/null \
     && setfacl -R -m u:"$HTTPDUSER":rwX -m u:"$(whoami)":rwX "$dir" 2>/dev/null; then
    return 0
  fi
  chmod -R a+rwX "$dir"
  return 1
}

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d' ' -f1)
[[ -n "$HTTPDUSER" ]] || HTTPDUSER=www-data

log info "Выставляю права на var и public/uploads для Sulu…"

used_chmod=0
apply_writable "$APP_PATH/var" || used_chmod=1
apply_writable "$APP_PATH/public/uploads" || used_chmod=1

if [[ $used_chmod == 0 ]]; then
  log success "Права на var и public/uploads выставлены"
else
  log success "Права на var и public/uploads выставлены (chmod: ACL недоступен на этом томе)"
fi
