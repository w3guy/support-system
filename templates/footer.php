<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>
        <?php if( get_option( Option::SHOW_FOOTER, Option\Defaults::SHOW_FOOTER ) == 'on' ) : ?>

            <footer id="footer">

                <div class="container">

                    <hr>

                    <p class="footer-text text-center"><?php echo get_option( Option::FOOTER_TEXT, Option\Defaults::FOOTER_TEXT ); ?></p>

                </div>

            </footer>

        <?php endif; ?>

        <script>

            var Globals = {
                ajax_url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                ajax_nonce: "<?php echo wp_create_nonce( 'support_ajax' ); ?>",
                strings: {
                    loading_tickets: "Loading Tickets...",
                    loading_generic: "Loading..."
                }
            };

        </script>

        <script type="text/template" class="ajax-loader-mask">

            <div class="ajax-loader">

                <div class="dot-container">

                    <div class="dot dot-1"></div>

                </div>

                <div class="dot-container">

                    <div class="dot dot-2"></div>

                </div>

                <div class="dot-container">

                    <div class="dot dot-3"></div>

                </div>

                <p class="text-center"><%= obj %></p>

            </div>

        </script>

        <script type="text/template" class="notice-inline">

            <div style="border-radius: 0; margin: 0" class="alert alert-success fade in">

                <a href="#" class="close" data-dismiss="alert">×</a><%= obj %>

            </div>

        </script>

        <script src="<?php echo home_url( 'wp-includes/js/underscore.min.js' ); ?>"></script>
        <script src="<?php echo home_url( 'wp-includes/js/jquery/jquery.js' ); ?>"></script>
        <script src="<?php echo $url . '/assets/lib/bootstrap/js/bootstrap.min.js'; ?>"></script>
        <script src="<?php echo $url . 'assets/lib/scrollingTabs/scrollingTabs.min.js'; ?>"></script>
        <script src="<?php echo $url . 'assets/lib/moment/moment.min.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/plugins.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/app.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/settings.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/ticket.js' ?>" ></script>
        <script src="<?php echo $url . 'assets/js/comment.js' ?>" ></script>

    </body>

</html>