#!/usr/local/bin/bash

cd $1/frontend || exit
npm ci || exit
npm run build || exit
mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt
exit 0