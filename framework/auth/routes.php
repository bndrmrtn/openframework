<?php

// custom routes for auth

controller::addRoute('auth/[any]','*custom:' . FRAMEWORK . '/auth/handle/index');