<?php

function copy_directory( $source, $destination ) {
    if ( is_dir( $source ) ) {
    @mkdir( $destination );
    $directory = dir( $source );
    while ( false !== ( $readdirectory = $directory->read() ) ) {
        if ( $readdirectory == '.' || $readdirectory == '..' ) {
            continue;
        }
        $PathDir = $source . '/' . $readdirectory; 
        if ( is_dir( $PathDir ) ) {
            copy_directory( $PathDir, $destination . '/' . $readdirectory );
            continue;
        }
        copy( $PathDir, $destination . '/' . $readdirectory );
    }

    $directory->close();
    } else {
        copy( $source, $destination );
    }
}