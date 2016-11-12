<?php

use SmartcatSupport\form\Form;
use const SmartcatSupport\TEXT_DOMAIN;

?>

<div class="ticket_editor">

    <form class="edit_ticket_form"
          data-action="<?php esc_attr_e( $action ); ?>"
          data-after="<?php esc_attr_e( $after ); ?>">

        <?php Form::form_fields( $editor_form ); ?>

        <div class="meta_fields">

            <?php Form::form_fields( $meta_form ); ?>

        </div>

        <div class="submit_button_wrapper">

            <button class="submit_button">

                <div class="status hidden"></div>

                <span class="text"
                      data-default="<?php _e( 'Save', TEXT_DOMAIN ); ?>"
                      data-success="<?php _e( 'Saved', TEXT_DOMAIN ); ?>"
                      data-fail="<?php _e( 'Error', TEXT_DOMAIN ); ?>"
                      data-wait="<?php _e( 'Saving', TEXT_DOMAIN ); ?>">

                          <?php _e( 'Save', TEXT_DOMAIN ); ?>

                    </span>

            </button>

        </div>

    </form>

</div>
