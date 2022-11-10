<?php

require __DIR__ . '/load/loader.php';

DEV\DEVLoader::load();

return DEV\DEVLoader::createApp();