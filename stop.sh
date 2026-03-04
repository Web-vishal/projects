#!/bin/bash

echo "🛑 Stopping Application..."

# Stop Docker backend + DB if running
if command -v docker >/dev/null 2>&1; then
  if docker info >/dev/null 2>&1; then
    echo "⏹  Stopping Docker containers (backend + db)..."
    (cd "$(dirname "$0")" && docker compose down)
  fi
fi

# Helper to kill anything listening on a given port
kill_on_port() {
  local port="$1"
  if command -v lsof >/dev/null 2>&1; then
    local pids
    pids=$(lsof -ti :"$port" || true)
    if [ -n "$pids" ]; then
      echo "⏹  Killing processes on port $port: $pids"
      kill $pids 2>/dev/null || true
    fi
  fi
}

# Stop Angular dev servers (default ports)
kill_on_port 4200
kill_on_port 4300

echo "✅ All known backend and Angular instances stopped."

