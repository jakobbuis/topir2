<?php

namespace Deployer;

require 'recipe/laravel.php';

// Configuration
set('application', 'Topir2');
set('repository', 'git@github.com:jakobbuis/topir2.git');
set('allow_anonymous_stats', false);

// Always migrate database
before('deploy:symlink', 'artisan:migrate');

// Always build production assets
task('build:frontend', function () {
    within('{{release_path}}', function () {
        run('npm ci');
        run('npm run production');
    });
});
before('deploy:symlink', 'build:frontend');

// Production
host('production')
    ->setHostname('topir.jakobbuis.nl')
    ->set('branch', 'main')
    ->set('remote_user', 'jakob')
    ->set('keep_releases', 3)
    ->set('deploy_path', '/srv/topir2/');
