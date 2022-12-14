@ECHO OFF

:: |=======================|
:: | Launch Mailhog Script |
:: |=======================|

CALL :REFRESH_DEPENDENCIES
EXIT /b %ERRORLEVEL%

REM SETX POKEROM "%USERPROFILE%\PhpstormProjects\pokerom"

:REFRESH_DEPENDENCIES
    REM escape newlines for multiple sequential commands using ` && <NUL ^`
    composer update && composer install && <NUL ^
    npm install && npm update && <NUL ^
    npm run build
EXIT /b 0
