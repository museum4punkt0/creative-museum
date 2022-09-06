#!/usr/local/bin/bash

cd $1/backend && composer install && bin/console doctrine:migrations:migrate --no-interaction