#!/usr/bin/env bash
set -euo pipefail

readonly REPO_URL="https://github.com/rizkyilhampra/laravel-mazer-starter"
readonly REQUIRED_PHP_VERSION="8.2"
readonly REQUIRED_DEPS=("git" "composer" "php" "unzip" "npm")

declare -A COLORS
if [[ -t 1 ]]; then
    COLORS=(
        [reset]='\033[0m'
        [red]='\033[0;31m'
        [green]='\033[0;32m'
        [yellow]='\033[0;33m'
        [blue]='\033[0;34m'
        [dim]='\033[0;2m'
        [bold]='\033[1m'
        [bold_green]='\033[1;32m'
        [bold_blue]='\033[1;34m'
    )
else
    COLORS=(
        [reset]='' [red]='' [green]='' [yellow]=''
        [blue]='' [dim]='' [bold]='' [bold_green]=''
        [bold_blue]=''
    )
fi

log() {
    local -r level=$1
    local -r message=$2
    case "$level" in
    error)
        echo -e "${COLORS[red]}error${COLORS[reset]}: $message" >&2
        exit 1
        ;;
    info) echo -e "${COLORS[dim]}$message${COLORS[reset]}" ;;
    success) echo -e "${COLORS[green]}$message${COLORS[reset]}" ;;
    warning) echo -e "${COLORS[yellow]}warning${COLORS[reset]}: $message" ;;
    esac
}

check_docker_port() {
    local default_port=$1
    local port_name=$2

    if ! docker container ls --format '{{.Ports}}' | grep -E "(0.0.0.0:|:::)${default_port}->"; then
        local port_found=false
        for port in $(seq "$((default_port + 1))" "$((default_port + 10))"); do
            if ! docker container ls --format '{{.Ports}}' | grep -E "(0.0.0.0:|:::)80->"; then
                echo "${port_name}=$port" >>.env
                port_found=true
                break
            fi
        done

        if ! $port_found; then
            log error "Failed to find an available port for ${port_name} (tried ports ${default_port+1}-${default_port+10})"
        fi
    fi
}

main() {
    echo -e "${COLORS[bold_blue]}Laravel Mazer Starter Setup${COLORS[reset]}"

    platform=$(uname -ms)
    if [[ ${OS:-} = Windows_NT ]]; then
        if [[ $platform != MINGW64* ]]; then
            log error "Windows is not supported. Use Arch Linux instead btw"
        fi
    fi

    local missing_deps=()
    for dep in "${REQUIRED_DEPS[@]}"; do
        if ! command -v "$dep" >/dev/null 2>&1; then
            missing_deps+=("$dep")
        fi
    done
    if [ ${#missing_deps[@]} -ne 0 ]; then
        log error "Missing required dependencies: ${missing_deps[*]}\nPlease install them before running this script."
    fi

    local current_version
    current_version=$(php -r "echo PHP_VERSION;" 2>/dev/null) || log error "Failed to get PHP version"
    if [[ "$(printf '%s\n' "$REQUIRED_PHP_VERSION" "$current_version" | sort -V | head -n1)" != "$REQUIRED_PHP_VERSION" ]]; then
        log error "PHP version must be >= ${REQUIRED_PHP_VERSION}. Current version: ${current_version}"
    fi

    local project_name
    echo -e "Enter project directory name or leave it empty to use same as repository name:"
    read -r project_name
    project_name=${project_name:-$(basename "$REPO_URL")}
    if [[ ! "$project_name" =~ ^[a-zA-Z0-9_-]+$ ]]; then
        log error "Invalid project name. Use only letters, numbers, hyphens, and underscores."
    fi
    if [ -d "$project_name" ]; then
        log error "Directory '$project_name' already exists"
    fi

    git clone --depth 1 "$REPO_URL" "$project_name"
    cd "$project_name"

    composer install --no-interaction
    cp .env.example .env
    php artisan key:generate

    rm -rf .git
    rm install.sh
    truncate -s 0 README.md

    git init

    $(command -v bun || npm) install

    git add .
    git commit -m "init"

    touch database/database.sqlite

    if command -v docker &>/dev/null && docker info --format '{{.ServerVersion}}' &>/dev/null; then
        check_docker_port 80 "APP_PORT"
        check_docker_port 5173 "VITE_PORT"

        ./vendor/bin/sail up -d
        ./vendor/bin/sail artisan migrate --seed --graceful --ansi
        ./vendor/bin/sail artisan ide-helper:generate
    else
        php artisan migrate --seed --graceful --ansi
        php artisan ide-helper:generate
    fi

    if ! command -v tmux >/dev/null 2>&1; then
        log warning "tmux is not installed. You need to run the development server manually."
        return
    fi

    tmux new-session -d -s "${project_name}"
    tmux new-window -t "${project_name}" -n "server" "composer run dev"

    if [[ -n "${TMUX:-}" ]]; then
        tmux switch-client -t "${project_name}"
        return
    fi

    tmux attach-session -t "${project_name}"
}

main
