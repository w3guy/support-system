<?php

namespace SmartcatSupport;

use Pimple\Container;

function init( $fs_context ) {
    $app = new Container();

    require_once 'app.php';

    add_shortcode( 'support-system', function() use ( $app ) {
        echo $app['renderer']->render( 'dash' );
    } );

    //<editor-fold desc="Enqueue Assets">
    add_action( 'wp_enqueue_scripts', function() use ( $app ) {
        wp_enqueue_script( 'datatables',
            $app['plugin_url'] . 'assets/lib/datatables/js/datatables.min.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_style( 'datatables',
            $app['plugin_url'] . 'assets/lib/datatables/css/datatables.min.css', [], PLUGIN_VERSION );

        wp_enqueue_script( 'tabular',
            $app['plugin_url'] . 'assets/lib/tabular.js', [ 'jquery' ], PLUGIN_VERSION );

        wp_enqueue_script( 'tinymce_js',
            includes_url( 'js/tinymce/' ) . 'wp-tinymce.php', [ 'jquery' ], false, true );

        wp_register_script( 'support_system_lib',
            $app['plugin_url'] . 'assets/js/app.js', [ 'jquery', 'jquery-ui-tabs' ], PLUGIN_VERSION );

        wp_localize_script( 'support_system_lib', 'SupportSystem', [ 'ajaxURL' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'support_system_lib' );

        wp_enqueue_script( 'support_system_script',
            $app['plugin_url'] . 'assets/js/script.js', [ 'jquery', 'jquery-ui-tabs', 'jquery-ui-core', 'support_system_lib' ], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_style',
            $app['plugin_url'] . 'assets/css/style.css', [], PLUGIN_VERSION );

        wp_enqueue_style( 'support_system_icons',
            $app['plugin_url'] . 'assets/icons.css', [], PLUGIN_VERSION );
    } );
    //</editor-fold>

    register_activation_hook( $fs_context, [ $app['installer'], 'activate' ] );
    register_deactivation_hook( $fs_context, [ $app['installer'], 'deactivate' ] );
}

function convert_html_chars( $text ) {
    $matches = [];

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}


function var_error_log( $object=null ){
    ob_start();                    // start buffer capture
    var_dump( $object );           // dump the values
    $contents = ob_get_contents(); // put the buffer into a variable
    ob_end_clean();                // end capture
    error_log( $contents );        // log contents of the result of var_dump( $object )
}