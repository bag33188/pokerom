@ECHO OFF

:: =====================
:: Launch Mailhog Script
:: =====================

REM Launches `mailhog.exe` for assistance
REM in testing email functionality within the application

CALL :LAUNCH_MAILHOG
EXIT /b %ERRORLEVEL%

:LAUNCH_MAILHOG
    C:\xampp\mailhog\mailhog.exe
EXIT /b 0
