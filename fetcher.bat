@ECHO OFF

SET mode=%1


SET ORIGIN_PATH=%USERPROFILE%\PhpstormProjects\pokerom
SET PROJ_DIR=%USERPROFILE%\PhpstormProjects\pokerom-dbs1


:switch-case-mode
  :: Call and mask out invalid call targets
  goto :switch-case-mode-%mode% 2>nul || (
    :: Default case
    echo Invalid option "%1"
  )
  goto :switch-case-end
  
  :switch-case-mode-destroy
  rmdir /S /Q pokerom-dbs1
  goto :switch-case-end    
  :switch-case-mode-fresh
  	if exist pokerom-dbs1 (rd /S /Q pokerom-dbs1)
  	MD pokerom-dbs1
    call :folders
    call :files
    goto :switch-case-end     
  :switch-case-mode-files
    call :files
    goto :switch-case-end
  :switch-case-mode-folders
    call :folders
    goto :switch-case-end
:switch-case-end
   exit /b 0

:folders
MKDIR %PROJ_DIR%\databases\scripts
MKDIR %PROJ_DIR%\databases\queries
MKDIR %PROJ_DIR%\connections
MKDIR %PROJ_DIR%\data
goto :EOF


:files
copy /Y  %ORIGIN_PATH%\scripts\db\pokerom_db.sql %PROJ_DIR%\databases\scripts\pokerom_db.sql 
copy /Y %ORIGIN_PATH%\scripts\db\pokerom_files.mongo.js %PROJ_DIR%\databases\scripts\pokerom_files.js 
copy /Y %ORIGIN_PATH%\scripts\db\queries\pokerom_db_indexes.sql %PROJ_DIR%\databases\queries\pokerom_db.indexes.sql 
copy /Y %ORIGIN_PATH%\scripts\db\queries\pokerom_files_indexes.mongo.js %PROJ_DIR%\databases\queries\pokerom_files.indexes.js 
copy /Y %ORIGIN_PATH%\private\schemas\.dsn.yml %PROJ_DIR%\connections\dsn.yaml  
XCOPY %ORIGIN_PATH%\data\* %PROJ_DIR%\data /S /I /Y
goto :EOF
