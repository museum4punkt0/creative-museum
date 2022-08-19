namespace :deploy do
    after :published, :frontend do
        desc "Build Frontend"
        on roles(:all) do
            # execute "/usr/local/bin/bash #{release_path}/scripts/frontend-install.sh #{release_path}"
            execute "bash -c \"cd #{release_path}/frontend && npm ci\""
            execute "bash -c \"node #{release_path}/frontend/node_modules/nuxt/bin/nuxt.js build\""
        end
    end
end

# cd $1/frontend || exit
# npm ci || exit
# node $1/frontend/node_modules/nuxt/bin/nuxt.js build || exit
# mkdir $1/frontend/tmp && touch $1/frontend/tmp/restart.txt
# exit 0