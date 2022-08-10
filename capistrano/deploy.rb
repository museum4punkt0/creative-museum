set :application, 'creative-museum'
set :repo_url, 'git@bitbucket.org:jwied/creative-museum.git'
set :keep_releases, 3
set :deploy_via, :remote_cache
set :php_cli, '/opt/plesk/php/8.0/bin/php'
set :ssh_cli, '/usr/bin/ssh'
set :log_level, :info
set :deploy_to, "~/#{fetch(:application)}-#{fetch(:stage)}"
set :composer_install_flags, '--no-dev --no-interaction --no-progress --optimize-autoloader'
set :use_sudo, true