@echo off
setlocal

cd /d "%~dp0"
php -d upload_max_filesize=10M -d post_max_size=12M -d memory_limit=256M -S localhost:8000