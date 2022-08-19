namespace :deploy do
    after :published, :frontend do
        desc "Build Frontend"
        on roles(:all) do
            execute "bash -c \"cd #{release_path}/frontend && npm ci\""
            execute "bash -c \"cd #{release_path}/frontend && node #{release_path}/frontend/node_modules/nuxt/bin/nuxt.js build\""
            execute "mkdir #{release_path}/frontend/tmp && touch #{release_path}/frontend/tmp/restart.txt"
        end
    end
end