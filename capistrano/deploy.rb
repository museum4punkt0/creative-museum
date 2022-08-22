set :application, 'creative-museum'
# set :repo_url, 'git@bitbucket.org:jwied/creative-museum.git'
set :keep_releases, 3
# set :deploy_via, :remote_cache
set :scm, :copy

set :php_cli, '/usr/local/bin/php'
set :ssh_cli, '/usr/bin/ssh'
set :log_level, :info
set :deploy_to, "~/#{fetch(:application)}-#{fetch(:stage)}"
set :use_sudo, false
set :pty, true

Rake::Task['deploy:updated'].prerequisites.delete('composer:install')