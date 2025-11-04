<?php

$providers = [
    App\Providers\AppServiceProvider::class,
];

// Only register Telescope if it's installed (dev dependency)
if (class_exists('Laravel\Telescope\Telescope')) {
    $providers[] = App\Providers\TelescopeServiceProvider::class;
}

return $providers;
