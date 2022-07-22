<?php

// custom routes for auth

Route::add('auth/[any]','*custom:' . FRAMEWORK . '/auth/handle/index');