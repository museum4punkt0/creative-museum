#!/usr/local/bin/bash

exec > /tmp/bash.log 2>&1
set -x

cd $1/frontend || exit
npm ci || exit
truss -f $1/frontend/node_modules/.bin/nuxt build || exit
mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt
exit 0