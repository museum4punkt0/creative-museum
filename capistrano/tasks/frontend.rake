namespace :deploy do
    after :published, :frontend do
        desc "Build Frontend"
        on roles(:all) do
            execute "bash -c \"cd #{release_path}/frontend && npm ci\""
            execute "bash -c \"export NODE_OPTIONS='--max_old_space_size=512 --v8-pool-size=2' && cd #{release_path}/frontend && node node_modules/nuxt/bin/nuxt.js build || exit 1\""
execute "mkdir #{release_path}/frontend/tmp && touch #{release_path}/frontend/tmp/restart.txt"
        end
    end
end