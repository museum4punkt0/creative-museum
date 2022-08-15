#!/bin/bash

cd $1/frontend && npm ci -verbose && npm run build -verbose && mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt && exit 0