set :deploy_config_path, File.expand_path('capistrano/deploy.rb')
set :stage_config_path, File.expand_path('capistrano/stages')

require 'capistrano/setup'
require 'capistrano/deploy'
require 'capistrano/scm/git'
install_plugin Capistrano::SCM::Git

# import 'capistrano/tasks/backend.rake'
import 'capistrano/tasks/frontend.rake'
# import 'capistrano/tasks/typo3.rake'
