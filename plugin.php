<?php
/**
 * Plugin Name: uCare - Support Ticket System
 * Author: Smartcat
 * Description: If you have customers, then you need uCare. A support ticket help desk for your customers featuring usergroups,agents,ticket status,filtering,searching all in one responsive app. The most robust support ticket system for WordPress. 
 * Version: 1.4.2
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * license: GPL V2
 *
 *
 * @package ucare
 * @since 1.0.0
 */
namespace ucare;


// Die if access directly
if ( !defined( 'ABSPATH' ) ) {
    die();
}

// Pull in constant declarations
include_once dirname( __FILE__ ) . '/constants.php';


// PHP Version check
if ( PHP_VERSION >= MIN_PHP_VERSION ) {


    // Pull in immediate dependencies
    include_once dirname( __FILE__ ) . '/includes/trait-data.php';
    include_once dirname( __FILE__ ) . '/includes/trait-singleton.php';


    // load the plugin text domain
    add_action( 'plugins_loaded', 'ucare\load_text_domain' );

    // Add custom action links to the plugins table row
    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ucare\add_plugin_action_links' );


    /**
     * Main plugin class
     *
     * @package ucare
     * @since 1.4.2
     */
    final class uCare {

        use Data;
        use Singleton;


        /**
         * Sets up includes and defines global constants.
         *
         * @since 1.4.2
         * @access protected
         * @return void
         */
        protected function initialize() {

            $this->do_defines();
            $this->do_includes();

            // All done
            do_action( 'ucare_loaded', $this );

        }


        /**
         * Define plugin constants.
         *
         * @since 14.2
         * @access private
         * @return void
         */
        private function do_defines() {

            define( 'UCARE_DIR', plugin_dir_path( __FILE__ ) );
            define( 'UCARE_URL', plugin_dir_url(  __FILE__ ) );

            define( 'UCARE_TEMPLATES_PATH', UCARE_DIR . 'templates/' );
            define( 'UCARE_PARTIALS_PATH',  UCARE_DIR . 'templates/partials/' );
            define( 'UCARE_INCLUDES_PATH',  UCARE_DIR . 'includes/'  );

        }


        /**
         * Include plugin files.
         *
         * @since 1.4.2
         * @access private
         * @return void
         */
        private function do_includes() {

            include_once dirname( __FILE__ ) . '/lib/mail/mail.php';


            include_once dirname( __FILE__ ) . '/includes/email-notifications.php';
            include_once dirname( __FILE__ ) . '/includes/cron.php';
            include_once dirname( __FILE__ ) . '/includes/extension-licensing.php';


            include_once dirname( __FILE__ ) . '/includes/class-field.php';
            include_once dirname( __FILE__ ) . '/includes/class-bootstrap-nav-walker.php';


            include_once dirname( __FILE__ ) . '/includes/functions.php';
            include_once dirname( __FILE__ ) . '/includes/functions-comment.php';
            include_once dirname( __FILE__ ) . '/includes/functions-user.php';
            include_once dirname( __FILE__ ) . '/includes/functions-template.php';
            include_once dirname( __FILE__ ) . '/includes/functions-sanitize.php';
            include_once dirname( __FILE__ ) . '/includes/functions-scripts.php';
            include_once dirname( __FILE__ ) . '/includes/functions-helpers.php';
            include_once dirname( __FILE__ ) . '/includes/functions-deprecated.php';
            include_once dirname( __FILE__ ) . '/includes/functions-widgets.php';
            include_once dirname( __FILE__ ) . '/includes/functions-field.php';
            include_once dirname( __FILE__ ) . '/includes/functions-public.php';
            include_once dirname( __FILE__ ) . '/includes/functions-shortcodes.php';
            include_once dirname( __FILE__ ) . '/includes/functions-post-support_ticket.php';
            include_once dirname( __FILE__ ) . '/includes/functions-taxonomy-ticket_category.php';


            if ( is_admin() ) {
                include_once dirname( __FILE__ ) . '/includes/admin/functions-menu.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-settings.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-admin-bar.php';
                include_once dirname( __FILE__ ) . '/includes/admin/functions-metabox.php';
            }


            if ( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
                include_once dirname( __FILE__ ) . '/lib/license/EDD_SL_Plugin_Updater.php';
            }

        }

    }


    /**
     * Get the main plugin instance.
     *
     * @todo call in plugins_loaded
     * @since 1.4.2
     * @return Singleton|uCare
     */
    function ucare() {
        return uCare::instance();
    }


    /**
     * Action to load the plugin text domain.
     *
     * @action plugins_loaded
     *
     * @since 1.0.0
     * @return void
     */
    function load_text_domain() {
        load_plugin_textdomain( 'ucare', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }


    /**
     * Action to add custom links to the plugins table row.
     *
     * @action plugin_action_links_{$basename}
     *
     * @param $links
     *
     * @since 1.0.0
     * @return array
     */
    function add_plugin_action_links( $links ) {

        if ( !get_option( Options::DEV_MODE ) ) {
            $links['deactivate'] = sprintf( '<span id="feedback-prompt">%s</span>', $links['deactivate'] );
        }

        $custom = array(
            'settings' => sprintf( '<a href="%1$s">%2$s</a>', menu_page_url( 'uc-settings', false ), __( 'Settings', 'ucare' ) )
        );

        return array_merge( $links, $custom );

    }


    /**
     * Boot the plugin.
     *
     * @todo move this to a plugins_loaded callback
     * @since 1.4.2
     */
    ucare();

    //<editor-fold desc="Legacy Boot">
    do_action( 'support_register_autoloader', include_once 'vendor/autoload.php' );
    Plugin::boot( PLUGIN_ID, PLUGIN_VERSION, __FILE__ );
    //</editor-fold>


} else {

    /**
     * Add an error in the admin dashboard if the server's PHP version does not meet the minimum requirements.
     *
     * @since 1.0.0
     */
    add_action( 'admin_notices', function () { ?>

        <div class="notice notice-error is-dismissible">
            <p>
                <?php _e( 'Your PHP version ' . PHP_VERSION . ' does not meet minimum' .
                          'requirements. uCare Support requires version 5.5 or higher', 'ucare' ); ?>
            </p>
        </div>

    <?php } );

}


/**
 * @since 1.4.2
 * @param string $path
 * @return string
 */
function resolve_path( $path = '' ) {
    return UCARE_DIR . $path;
}


/**
 * @param string $path
 * @since 1.4.2
 * @return string
 */
function resolve_url( $path = '' ) {
    return UCARE_URL . $path;
}
