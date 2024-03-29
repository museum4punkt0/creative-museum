namespace :deploy do
    after :published, :frontend do
        desc "After deployment stuff"
        on roles(:all) do
            execute "bash -c \"cd #{release_path}/backend && bin/console doctrine:migrations:migrate --no-interaction || exit 1\""
            execute "bash -c \"cd #{release_path}/backend && bin/console cache:clear\""
            execute "bash -c \"cd #{release_path}/typo3 && vendor/bin/typo3cms database:updateschema\""
            execute "bash -c \"cd #{release_path}/typo3 && vendor/bin/typo3cms install:fixfolderstructure\""
            execute "bash -c \"cd #{release_path}/typo3 && vendor/bin/typo3 extension:setup\""
            execute "bash -c \"cd #{release_path}/typo3 && vendor/bin/typo3 cache:flush\""
            execute "bash -c \"/var/www/.npm-global/bin/pm2 startOrRestart /var/www/creative-museum-#{fetch(:stage)}/ecosystem.config.js\""
            execute "bash -c \"curl #{fetch(:backend_url)}/flush.php && rm #{release_path}/backend/public/flush.php\""
        end
    end
end