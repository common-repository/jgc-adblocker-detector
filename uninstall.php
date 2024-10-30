<?php

if( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();

// Remove options
delete_option( 'jgcabd_options' );
