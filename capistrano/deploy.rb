set :application, 'creative-museum'
set :repo_url, 'git@bitbucket.org:jwied/creative-museum.git'
set :keep_releases, 3
set :deploy_via, :remote_cache
set :linked_dirs, fetch(:linked_dirs, []).push('typo3/public/fileadmin', 'typo3/public/typo3temp', 'typo3/public/uploads')
set :linked_files, fetch(:linked_files, []).push('backend/.env', 'typo3/.env')
set :php_cli, '/opt/plesk/php/8.0/bin/php'
set :ssh_cli, '/usr/bin/ssh'
set :log_level, :info
set :deploy_to, "~/html/#{fetch(:application)}-#{fetch(:stage)}"
set :composer_install_flags, '--no-dev --no-interaction --no-progress --optimize-autoloader'