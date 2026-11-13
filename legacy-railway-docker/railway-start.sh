#!/bin/sh
set -eu
PORT="${PORT:-8080}"
exec apache2-foreground -D "PORT=${PORT}"
