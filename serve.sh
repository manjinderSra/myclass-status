#!/bin/bash

# Set increased PHP memory limits
export PHP_INI_SCAN_DIR=/dev/null
php -d upload_max_filesize=100M -d post_max_size=100M -d memory_limit=512M artisan serve --host=192.168.1.15 --port=8000 