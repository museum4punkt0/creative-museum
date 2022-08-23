namespace :deploy do
    after :published, :frontend do
        desc "Restart PM2"
        on roles(:all) do
            execute "bash -c \"cd #{release_path}/backend && bin/console cache:clear\""
            execute "bash -c \"/var/www/.npm-global/bin/pm2 restart blm_cm\""
        end
    end
end