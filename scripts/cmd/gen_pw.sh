#!/usr/bin/env bash

# ========================
# Generate Password Script
# ========================

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
