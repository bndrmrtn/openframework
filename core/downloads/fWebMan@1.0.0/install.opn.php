<?php

return new PackageInstallation([
     'pkgname' => 'fWebMan',
     'pkgversion' => '1.0.0',
     'pkgCopyFilesTo' => [
          PackageInstallation::cp(__DIR__ . '/pkg/__of_views.fwm', )
     ]
]);