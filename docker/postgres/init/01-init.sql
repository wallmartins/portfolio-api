-- PostgreSQL initialization script
-- This script runs automatically when the container is first created

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Grant all privileges to the database user
GRANT ALL PRIVILEGES ON DATABASE portfolio TO portfolio;
