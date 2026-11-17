@echo off
setlocal
cd /d "%~dp0"
if not exist "index.php" (
    echo CareerPulse: index.php est introuvable dans ce dossier.
    echo Extrais ce fichier dans le dossier racine du projet CareerPulse.
    pause
    exit /b 1
)
if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -S localhost:8000 -t "%~dp0"
) else (
    php -S localhost:8000 -t "%~dp0"
)
pause
