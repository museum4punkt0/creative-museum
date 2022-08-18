#!/usr/local/bin/bash

cd $1/frontend || exit
npm ci || exit
node --max-old-space-size=800 $1/frontend/node_modules/nuxt/bin/nuxt.js build || exit
mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt
exit 0