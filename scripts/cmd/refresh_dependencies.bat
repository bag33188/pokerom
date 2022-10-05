@ECHO OFF

REM |=======================|
REM | Launch Mailhog Script |
REM |=======================|

CALL :REFRESH_DEPENDENCIES
EXIT /b %ERRORLEVEL%

:REFRESH_DEPENDENCIES
    composer install && composer update && <NUL ^
    npm install && npm update && <NUL ^
    npm run build
EXIT /b 0
