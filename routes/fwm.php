<?php

/**
 * Attention!
 * Please do not modify this file, it contains all the routes for the web interface
 */

use App\Controller\OF\FrameworkWebManagerController as FWM;
use Routing\Route;

Route::prefix('/dev/fwm');

Route::get('/')->name('pkg.fwm.index')->control([FWM::class, 'index']);

Route::get('/routes')->name('pkg.fwm.routes')->control([FWM::class, 'routes']);

Route::get('/controllers')->name('pkg.fwm.controllers')->control([FWM::class, 'controllers']);

Route::get('/models')->name('pkg.fwm.models')->control([FWM::class, 'models']);

Route::get('/database')->name('pkg.fwm.database')->control([FWM::class, 'database']);