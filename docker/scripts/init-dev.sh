#!/bin/bash
set -e

echo "🚀 Initializing Portfolio API - Development Environment"

# Wait for Redis to be ready
echo "⏳ Waiting for Redis..."
sleep 2

# Create database directory if it doesn't exist
mkdir -p /opt/www/runtime

# Create SQLite database if it doesn't exist
if [ ! -f /opt/www/runtime/database.sqlite ]; then
    echo "📦 Creating SQLite database..."
    touch /opt/www/runtime/database.sqlite
    chmod 666 /opt/www/runtime/database.sqlite
fi

# Run migrations
echo "🗄️  Running migrations..."
php bin/hyperf.php migrate

# Run seeders
echo "🌱 Running seeders..."
php bin/hyperf.php db:seed

echo "✅ Development environment initialized successfully!"
echo "🌐 Server will start on http://localhost:9501"
