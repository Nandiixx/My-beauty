@echo off
echo Iniciando servidor PHP...
echo.
echo O servidor sera iniciado em: http://localhost:8000
echo.
echo Para parar o servidor, pressione Ctrl+C
echo.
cd /d "%~dp0"
php -S localhost:8000
pause

