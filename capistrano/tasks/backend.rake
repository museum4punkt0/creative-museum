namespace :deploy do
    after :published, :backend do
        desc "Build Backend"
        on roles(:all) do
            execute "sh #{release_path}/scripts/backend-install.sh #{release_path}"
        end
    end
end