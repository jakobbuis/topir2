<?php

namespace Deployer;

require 'recipe/laravel.php';

// Configuration
set('application', 'Topir2');
set('repository', 'git@github.com:jakobbuis/topir2.git');
set('allow_anonymous_stats', false);

// Always migrate database
before('deploy:symlink', 'artisan:migrate');

// Production
host('topir.jakobbuis.nl')
    ->stage('production')
    ->set('deploy_path', '/srv/topir2/');
