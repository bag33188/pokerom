#!/usr/bin/bash

# ==============
# Compile Script
# ==============

:<< --MULTILINE-COMMENT--
Permissions (Unix)
------------------
add to current permissions
$ cd scripts/cmd
$ chmod +x ./compile.sh
set current permissions
$ cd scripts/cmd
$ chmod 755 ./compile.sh
--MULTILINE-COMMENT--

:<< --MULTILINE-COMMENT--
Make sure you are in Bash.
$ echo $0
/usr/bin/bash # bash
--MULTILINE-COMMENT--

compile() {
    bold=$(tput bold)
    normal=$(tput sgr0)
    target_PWD=$(readlink -f .)
    current_folder="${target_PWD##*/}"

    if [[ "$current_folder" = "scripts" ]]; then
        cd ..
    fi
    if [[ "$current_folder" = "cmd" ]]; then
        cd ../..
    fi

    composer install && composer update
    npm install && npm update
    npm run build

    node scripts/helpers/seeds.test.js

    git status
    git add . && git commit -m "update code base" && git push

    # printf "\n${bold}%s${normal}\n" "Finished!"

    echo -e "\n${bold}Finished!${normal}"
}

compile

exit 0;
