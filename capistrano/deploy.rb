set :application, 'creative-museum'
set :keep_releases, 3
set :scm, :copy

set :php_cli, '/usr/local/bin/php'
set :ssh_cli, '/usr/bin/ssh'
set :log_level, :info
set :deploy_to, "~/#{fetch(:application)}-#{fetch(:stage)}"
set :use_sudo, false
set :pty, true

set :linked_dirs, fetch(:linked_dirs, []).push('typo3/public/fileadmin', 'typo3/public/typo3temp', 'typo3/public/uploads', 'backend/public/media')
set :linked_files, ['backend/.env', 'typo3/.env', 'frontend/.env', 'backend/config/jwt/public.pem']

Rake::Task['deploy:updated'].prerequisites.delete('composer:install')