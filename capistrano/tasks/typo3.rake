namespace :deploy do
    after :published, :typo3 do
        desc "Build TYPO3"
        on roles(:all) do
            execute "sh #{release_path}/scripts/typo3-install.sh #{release_path}"
        end
    end
end