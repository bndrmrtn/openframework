<?php

// use the logout function
Auth::logout();

// simply redirect to the home page
location(BASE_URL . '/auth/login');