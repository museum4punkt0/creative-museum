set :stage, :staging

server 'vpro0803.proserver.punkt.de',
   user: 'proserver',
   ssh_options: {
      user: 'proserver',
   }

set :deploy_to, '/var/www/creative-museum-staging/'
set :branch,  'develop'
set :tmp_dir, '/var/www/creative-museum-staging/tmp'
set :linked_dirs, fetch(:linked_dirs, []).push('typo3/public/fileadmin', 'typo3/public/typo3temp', 'typo3/public/uploads')
set :linked_files, ['backend/.env', 'typo3/.env']