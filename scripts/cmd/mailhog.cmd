@ECHO OFF

:: =====================
:: Launch Mailhog Script
:: =====================

REM Launches `mailhog.exe` for assistance
REM in testing email functionality within the application

CALL :LAUNCH_MAILHOG
EXIT /b %ERRORLEVEL%

:LAUNCH_MAILHOG
    START "mailhog server" http://127.0.0.1:8025
    C:\xampp\mailhog\mailhog.exe
EXIT /b 0
