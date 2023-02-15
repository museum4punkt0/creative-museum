set :stage, :production

server 'vpro0803.proserver.punkt.de',
   user: 'proserver',
   roles: %w{app},
   ssh_options: {
       user: 'proserver',
       auth_methods: %w(publickey)
   }

set :php_cli, '/usr/local/bin/php'
set :ssh_cli, '/usr/bin/ssh'
set :tmp_dir, '/var/www/creative-museum-production/tmp'
set :backend_url, 'https://backend.creativemuseum.landemuseum.de'