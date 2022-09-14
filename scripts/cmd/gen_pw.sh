#!/usr/bin/env bash

# ========================
# Generate Password Script
# ========================

:<< --MULTILINE-COMMENT--
Permissions (Unix)
------------------

add to current permissions
$ cd scripts/cmd
$ chmod +x ./gen_pw.sh

set current permissions
$ cd scripts/cmd
$ chmod 755 ./gen_pw.sh
--MULTILINE-COMMENT--

gen_pw() {
  export NODE_ENV=development
  target_PWD=$(readlink -f .)
  current_folder="${target_PWD##*/}"
    if [[ "$current_folder" = "scripts" ]]; then
        # echo 'im in scripts'
        cd ..
    fi
    if [[ "$current_folder" = "cmd" ]]; then
        # echo 'im in cmd'
        cd ../..
    fi
  pw_gen_script_location="./scripts/helpers/password-hasher.js"
  salt_val=$1
  node $pw_gen_script_location --salt="$salt_val"
}

gen_pw "$@"

exit 0;
