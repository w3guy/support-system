<?php

use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;

?>

<div id="comment-<?php echo $comment->comment_ID; ?>"
     data-id="<?php esc_attr_e( $comment->comment_ID ); ?>"
     class="comment support_card">

    <div class="status_bar">

        <div class="image_wrapper">

            <?php echo get_avatar( $comment, 36 ); ?>

        </div>

        <div class="meta_wrapper">

            <p class="author_name"><?php esc_html_e( $comment->comment_author ); ?></p>

            <p class="date_posted">

                <?php _e( human_time_diff( strtotime( $comment->comment_date ), current_time( 'timestamp' ) ) . ' ago', Plugin::ID ); ?>

            </p>

        </div>

        <div class="actions">

            <?php if ( $comment->user_id == wp_get_current_user()->ID && current_user_can( 'edit_comments' ) && $comments_enabled ) : ?>

                <span class="trigger icon-bin delete-comment" data-id="<?php echo $comment->comment_ID; ?>"></span>
                <span class="trigger icon-pencil edit-comment"></span>

            <?php endif; ?>

        </div>

    </div>

    <div class="inner">

        <div class="content"><?php echo $comment->comment_content; ?></div>

        <?php if( $comments_enabled ) : ?>

            <div class="editor" style="display: none">

                <form class="edit_comment_form">

                    <textarea name="content"><?php echo $comment->comment_content; ?></textarea>

                    <input type="hidden" name="comment_id" value="<?php echo $comment->comment_ID; ?>">

                    <?php wp_comment_form_unfiltered_html_nonce(); ?>

                    <div class="button_wrapper">

                        <button type="button" class="trigger button button-primary cancel" data-action="cancel_comment_edit">

                            <?php _e( get_option( Option::CANCEL_BTN_TEXT, Option\Defaults::CANCEL_BTN_TEXT ) ); ?>

                        </button>

                        <button type="input" class="button button-cancel">

                            <?php _e( get_option( Option::SAVE_BTN_TEXT, Option\Defaults::SAVE_BTN_TEXT ) ); ?>

                        </button>

                    </div>

                </form>

            </div>

        <?php endif; ?>

    </div>

</div>


