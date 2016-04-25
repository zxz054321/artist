@echo off
rd /s /q vendor
composer install --no-dev
composer dump-autoload --optimize
pause