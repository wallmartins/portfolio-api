#!/bin/bash
set -e

echo "🚀 Initializing Portfolio API - Production Environment"

# Wait for PostgreSQL to be ready
echo "⏳ Waiting for PostgreSQL..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -c '\q' 2>/dev/null; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 1
done

echo "✅ PostgreSQL is ready!"

# Wait for Redis
echo "⏳ Waiting for Redis..."
sleep 2

# Run migrations
echo "🗄️  Running migrations..."
php bin/hyperf.php migrate --force

# Run seeders (optional in production)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "🌱 Running seeders..."
    php bin/hyperf.php db:seed
fi

echo "✅ Production environment initialized successfully!"
