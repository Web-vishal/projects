#!/bin/bash

# User Management System - Setup Script
# This script sets up the database and starts the servers

echo "🚀 User Management System - Setup Script"
echo "=========================================="
echo ""

# Check if MySQL is running
echo "📊 Checking MySQL connection..."
if ! mysql -u root -e "SELECT 1" &> /dev/null; then
    echo "❌ Error: MySQL is not running or credentials are incorrect"
    echo "Please start MySQL and update credentials in backend/config/database.php"
    exit 1
fi

echo "✅ MySQL is running"
echo ""

# Create database and import schema
echo "📦 Setting up database..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS software;"

if [ -f "backend/database.sql" ]; then
    mysql -u root software < backend/database.sql
    echo "✅ Database created and schema imported"
else
    echo "❌ Error: database.sql not found"
    exit 1
fi

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📝 Next steps:"
echo "1. Start the PHP backend server:"
echo "   cd backend && php -S localhost:8080"
echo ""
echo "2. In a new terminal, start the Vue frontend:"
echo "   cd photograph && npm run dev"
echo ""
echo "3. Open your browser to http://localhost:5173"
echo ""
echo "🔐 Demo credentials:"
echo "   Username: admin"
echo "   Password: admin123"
echo ""
