@ECHO OFF

SET ORIGIN_PATH=%USERPROFILE%\PhpstormProjects\pokerom
SET PROJ_DIR=%USERPROFILE%\PhpstormProjects\pokerom-dbs

REM SET PROJ_DIR=%USERPROFILE%\DatagripProjects\pokerom-dbs


XCOPY /Y /I %ORIGIN_PATH%\scripts\db\pokerom_db.sql %PROJ_DIR%\databases\scripts
XCOPY /Y /I %ORIGIN_PATH%\scripts\db\queries\pokerom_db_indexes.sql %PROJ_DIR%\databases\queries\pokerom_db.indexes.sql
XCOPY /Y /I %ORIGIN_PATH%\scripts\db\queries\pokerom_files_indexes.mongo.js %PROJ_DIR%\queries\pokerom_files.indexes.js



rem.||(
mkdir pokerom-dbs
cd pokerom-dbs
mkdir commands
mkdir database
cd database
mkdir scripts
mkdir queries
mkdir data
cp $pokerom\scripts\db\pokerom_db.sql scripts
cp $pokerom\scripts\db\pokerom_files.mongo.js scripts\pokerom_files.js
cp $pokerom\scripts\db\queries\pokerom_files_indexes.mongo.js queries\pokerom_files_indexes.js
cp $pokerom\scripts\db\queries\pokerom_db_indexes.sql queries
ls queries
ls scripts
ls -a
cp $pokerom\private\schemas\.dsn.yml dsn.yaml
mkdir connections
cd ..
mv database\ databases\
ls
cd databases
ls
ls queries
cp $pokerom\data\dump\*.json data
ls data
ls -a
mv dsn.yaml connections
ls connections
touch ..\commands\fetch.cmd
ls ..\commands
)
