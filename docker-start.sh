#!/bin/bash

# Fix storage permissions at runtime (no logs needed - using stderr)
mkdir -p storage/framework/cache storage/framework/sessions \
    storage/framework/views bootstrap/cache public/uploads

# Make storage writable for all (Render uses different UIDs)
chmod -R 777 storage bootstrap/cache

# Start Apache
apache2-foreground
