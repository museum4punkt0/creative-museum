#!/usr/local/bin/bash

cd $1/frontend || exit
npm ci || exit
$1/frontend/node_modules/.bin/nuxt build || exit
mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt
exit 0