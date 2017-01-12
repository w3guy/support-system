<?php

use SmartcatSupport\descriptor\Option;
use const SmartcatSupport\PLUGIN_ID;


?>

<div class="container">

    <div class="row">
        
        <div class="col-sm-12">
        
            <?php if ( current_user_can( 'create_support_tickets' ) ) : ?>

                <a href="<?php echo admin_url( 'admin-ajax.php' ) . '?action=support_new_ticket' ?>"  rel="modal:open" class="button">

                    <?php _e( get_option( Option::CREATE_BTN_TEXT, Option\Defaults::CREATE_BTN_TEXT ), PLUGIN_ID ); ?>

                </a>

            <?php endif; ?>

        </div>
        
        <div id="support_system" class="col-sm-12">
            
            <div class="tabs">

                <ul>

                    <li>

                        <a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=support_list_tickets">

                            <?php _e( 'Tickets', PLUGIN_ID ); ?>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>

