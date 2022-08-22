namespace :deploy do
    after :published, :frontend do
        desc "Build Frontend"
        on roles(:all) do
            execute "bash -c \"cd #{release_path}/frontend && npm ci\""
            execute "bash -c \" cd #{release_path}/frontend && node --max_old_space_size=1024 --v8-pool-size=4 node_modules/bin/nuxt.js build || exit 1\""
execute "mkdir #{release_path}/frontend/tmp && touch #{release_path}/frontend/tmp/restart.txt"
        end
    end
end