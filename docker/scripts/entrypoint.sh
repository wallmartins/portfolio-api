#!/bin/bash
set -e

echo "Starting Portfolio API..."
echo "Environment: ${APP_ENV:-development}"
echo "Database Driver: ${DB_DRIVER:-sqlite}"

# Function to wait for PostgreSQL
wait_for_postgres() {
    if [ "$DB_DRIVER" = "pgsql" ]; then
        echo "Waiting for PostgreSQL to be ready..."
        until pg_isready -h "${DB_HOST}" -p "${DB_PORT}" -U "${DB_USERNAME}" > /dev/null 2>&1; do
            echo "PostgreSQL is unavailable - sleeping"
            sleep 2
        done
        echo "PostgreSQL is ready!"
    fi
}

# Function to wait for Redis
wait_for_redis() {
    echo "Waiting for Redis to be ready..."
    until redis-cli -h "${REDIS_HOST}" -p "${REDIS_PORT}" ping > /dev/null 2>&1; do
        echo "Redis is unavailable - sleeping"
        sleep 2
    done
    echo "Redis is ready!"
}

# Wait for services
wait_for_postgres
wait_for_redis

# Create database file for SQLite
if [ "$DB_DRIVER" = "sqlite" ]; then
    echo "Ensuring SQLite database file exists..."
    mkdir -p "$(dirname ${DB_DATABASE})"
    touch "${DB_DATABASE}"
    chmod 666 "${DB_DATABASE}"
fi

# Run migrations
echo "Running database migrations..."
php bin/hyperf.php migrate --force

# Generate Swagger documentation
echo "Generating Swagger documentation..."
php bin/hyperf.php gen:swagger || echo "Swagger generation skipped"

echo "Starting server..."
exec "$@"
