@echo off

:: Establecer permisos de escritura en los directorios necesarios usando PowerShell
powershell -Command "Start-Process cmd -ArgumentList '/c icacls \"storage\" /grant Everyone:(OI)(CI)F /T' -Verb runAs"
powershell -Command "Start-Process cmd -ArgumentList '/c icacls \"bootstrap/cache\" /grant Everyone:(OI)(CI)F /T' -Verb runAs"

:: Inicializar la base de datos
php artisan migrate:fresh --seed
php artisan initialize:database

:: Iniciar el servidor local
start /B php -S localhost:8000 -t public

:: Esperar unos segundos para asegurarse de que el servidor esté en funcionamiento
timeout /t 5 /nobreak >nul

:: Abrir el navegador predeterminado apuntando a la URL de la aplicación Laravel

start php artisan serve --host=localhost --port=8000
start http://localhost:8000/auth/login