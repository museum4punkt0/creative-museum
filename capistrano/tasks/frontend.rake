namespace :deploy do
    after :published, :frontend do
        desc "Build Frontend"
        on roles(:all) do
            execute "bash #{release_path}/scripts/frontend-install.sh #{release_path}"
        end
    end
end