@ECHO OFF

:: |=======================|
:: | Launch Mailhog Script |
:: |=======================|

CALL :REFRESH_DEPENDENCIES
EXIT /b %ERRORLEVEL%

REM SETX POKEROM "%USERPROFILE%\PhpstormProjects\pokerom" /m

:REFRESH_DEPENDENCIES
    REM escape newlines for sequencing commands
    composer install && composer update && <NUL ^
    npm install && npm update && <NUL ^
    npm run build
EXIT /b 0
