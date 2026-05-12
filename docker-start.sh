#!/bin/bash

# Fix storage permissions at runtime
mkdir -p storage/framework/cache storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache public/uploads

# Make storage writable for all (Render uses different UIDs)
chmod -R 777 storage bootstrap/cache

# Create log file if it doesn't exist
touch storage/logs/laravel.log
chmod 777 storage/logs/laravel.log

# Start Apache
apache2-foreground
