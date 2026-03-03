#!/bin/bash

echo "🚀 Starting Application..."

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check if Docker is running
if command_exists docker && docker info >/dev/null 2>&1; then
    echo "✅ Docker is running. Starting containers..."
    docker-compose up -d
    echo "✅ Backend and Database started in Docker."
    
    echo "🚀 Starting Frontend..."
    cd frontend && npm run dev
    exit 0
fi

# Fallback to local PHP/MySQL if Docker is not available
echo "⚠️ Docker is not running or not installed."
echo "🔄 Attempting to run with local PHP and MySQL..."

if ! command_exists php; then
    echo "❌ PHP is not installed."
    echo "👉 Please install Docker Desktop OR install PHP and MySQL manually."
    exit 1
fi

if ! command_exists mysql; then
    echo "❌ MySQL is not installed."
    echo "👉 Please install Docker Desktop OR install PHP and MySQL manually."
    exit 1
fi

# Check if MySQL is running
if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
    echo "❌ MySQL is not running."
    echo "👉 Please start your MySQL server."
    exit 1
fi

# Start Backend locally
echo "🚀 Starting PHP Backend..."
cd backend && php -S localhost:8080 &
BACKEND_PID=$!
echo "✅ Backend running on port 8080 (PID: $BACKEND_PID)"

# Start Frontend
echo "🚀 Starting Frontend..."
cd ../frontend && npm run dev

# Cleanup on exit
trap "kill $BACKEND_PID" EXIT
