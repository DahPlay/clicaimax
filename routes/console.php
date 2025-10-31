<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Torann\GeoIP\Facades\GeoIP;

Artisan::command('play', function () {
    $geoData = "Teste";

    dd($geoData);
});

