#!/bin/bash

# Task Management System Database Setup Script
# This script sets up the database and creates the admin user

set -e  # Exit on any error

echo "=== Task Management System Database Setup ==="
echo

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "❌ Error: .env file not found!"
    echo "Please create a .env file with your database and admin credentials."
    exit 1
fi

# Load environment variables
source <(grep -E '^(DB_|ADMIN_)' .env | sed 's/^/export /')

# Check required environment variables
if [ -z "$DB_HOST" ] || [ -z "$DB_USERNAME" ] || [ -z "$DB_NAME" ]; then
    echo "❌ Error: Missing required database environment variables!"
    echo "Please check your .env file for DB_HOST, DB_USERNAME, and DB_NAME"
    exit 1
fi

echo "📋 Database Configuration:"
echo "  Host: ${DB_HOST}"
echo "  Username: ${DB_USERNAME}"
echo "  Database: ${DB_NAME}"
echo

echo "🔧 Setting up database..."

# Create a temporary schema file with replaced placeholders
echo "Creating database and tables..."
TEMP_SCHEMA=$(mktemp)
sed "s/\${DB_NAME}/${DB_NAME}/g" database/schema.sql > "$TEMP_SCHEMA"

# Execute the schema with replaced placeholders
mysql -h"${DB_HOST}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" < "$TEMP_SCHEMA"

# Clean up temporary file
rm "$TEMP_SCHEMA"

if [ $? -eq 0 ]; then
    echo "✅ Database and tables created successfully"
else
    echo "❌ Error creating database and tables"
    exit 1
fi

echo

# Insert admin user
echo "👤 Setting up admin user..."
php database/insert_admin_user.php

if [ $? -eq 0 ]; then
    echo "✅ Admin user setup completed"
else
    echo "❌ Error setting up admin user"
    exit 1
fi

echo
echo "🎉 Database setup completed successfully!"
echo
echo "📝 Next steps:"
echo "  1. Start the PHP server: php -S 127.0.0.1:8000 -t public"
echo "  2. Visit http://127.0.0.1:8000 in your browser"
echo "  3. Login with the admin credentials from your .env file"
echo
echo "🔐 Admin credentials:"
echo "  Username: ${ADMIN_USERNAME}"
echo "  Email: ${ADMIN_EMAIL}"
echo "  Password: (check your .env file for ADMIN_PASSWORD)"
echo 