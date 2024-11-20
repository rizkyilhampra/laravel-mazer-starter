#!/usr/bin/env bash

if command -v docker &>/dev/null && docker info --format '{{.ServerVersion}}' &>/dev/null; then
    ./vendor/bin/sail ps | grep -q 'Up' || ./vendor/bin/sail up -d

    bunx concurrently \
        -c "#93c5fd,#c4b5fd,#fb7185" \
        "./vendor/bin/sail logs --follow" \
        "./vendor/bin/sail artisan pail --timeout=0" \
        "./vendor/bin/sail bun run dev" \
        --names=server,logs,vite \
        --teardown "./vendor/bin/sail down"
else
    concurrently \
        -c "#93c5fd,#c4b5fd,#fb7185" \
        "php artisan serve" \
        "php artisan pail --timeout=0" \
        "bun run dev" \
        --names=server,logs,vite
fi
