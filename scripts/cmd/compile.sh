#!/usr/bin/bash



compile() {
    bold=$(tput bold)
    normal=$(tput sgr0)
    target_PWD=$(readlink -f .)
    current_folder="${target_PWD##*/}"

    if [[ "$current_folder" != "pokerom" ]]; then
        cd $pokerom
    fi

    composer install && composer update
    npm install && npm update
    npm run build

    git status

    git add . && git commit -m "update code base" && git push

    echo -e "\n${bold}Finished!${normal}"
    date +"%r"
}

compile

exit 0;
