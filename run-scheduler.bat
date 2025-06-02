@echo off
cd /d "d:\xampp\htdocs\BBvb-main"
php artisan schedule:run >> storage/logs/scheduler.log 2>&1
