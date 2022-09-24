@ECHO OFF

:: =====================
:: Launch Mailhog Script
:: =====================

REM.||(
  Windows Batchfile
  -----------------
  This script cannot be run on a Unix machine.
  Batch is a scripting language invented for DOS.
  It has no compatibility with OSX or Linux.
  This file can only be run on a Windows machine.
)


:MAILHOG

    REM assumes `xampp\mailhog` is added to PATH

    C:\xampp\mailhog\mailhog.exe

EXIT /B 0
