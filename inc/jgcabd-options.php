<?php

add_action( 'admin_enqueue_scripts', 'jgcabd_admin_enqueue_scripts' );
function jgcabd_admin_enqueue_scripts($hook) {

	wp_enqueue_script('jgcabd-options-scripts', plugins_url('../js/jgcabd-options-scripts.js', __FILE__), array('jquery'), JGCABD_PLUGIN_VERSION, true);

}

add_action( 'admin_menu', 'jgcabd_add_menu' );
function jgcabd_add_menu() {

    add_options_page ( 'JGC AdBlocker Detector', 'JGC AdBlocker Detector', 'manage_options', 'jgc-adblocker-detector', 'jgcabd_options_page' );

    add_action ( 'admin_init', 'jgcabd_register_setting' );

}

function jgcabd_register_setting() {

    register_setting ( 'jgcabd_options_group', 'jgcabd_options', 'jgcabd_sanitize_options' );

}

function jgcabd_sanitize_options($input) {

    $input['enable_adblocker_detector']    = ( $input['enable_adblocker_detector'] == 'on' ) ? 'on' : '';
    $input['modal_box_heading']            = sanitize_text_field( $input['modal_box_heading']);
    $input['modal_box_content']            = wp_kses_post(trim($input['modal_box_content']));
	$input['disable_for_administrators']   = ( $input['disable_for_administrators'] == 'on' ) ? 'on' : '';
    $input['display_close_button']         = sanitize_text_field($input['display_close_button']);
    $input['$ad_containers_css_selectors'] = sanitize_text_field( $input['$ad_containers_css_selectors']);
    $input['$text_containers_content']     = wp_kses_post(trim($input['$text_containers_content']));
    $input['max_views_with_adblock']       = sanitize_text_field( $input['max_views_with_adblock'] );
    $input['enable_g_analytics_event']     = ( $input['enable_g_analytics_event'] == 'on' ) ? 'on' : '';

    return $input;

}

function jgcabd_options_page(){
    ?>
    <style type="text/css">
        .settings-page-heading{
            background-color:#0073AA;
            color:white;
            line-height:2;
            margin:0 0 40px -20px;
            padding-left:20px;
            padding-top: 5px;
            padding-bottom: 5px;
        }
        .settings-page-heading h1, .settings-page-heading h3{
            display: inline;
            margin:0;
            color:white;
        }
        .settings-page-heading .author{
            float:right;
            padding-right: 28px;
            padding-top:7px;
        }
        .wrap{
            box-sizing: border-box;
        }
        .wrap .col-left{
            box-sizing: border-box;
        }
        .wrap .col-left h2{
            margin-top: 0;
            font-size: 24px;
        }
        th h3{
            margin: 0;
        }
        .wrap .col-right{
            box-sizing: border-box;
        }
        .wrap .col-right .info-box{
            background-color: white;
            border: 1px solid #e5e5e5;
			box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        .wrap .col-right .info-box-heading{
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-bottom: 1px solid #eeeeee;
        }
        .wrap .col-right .info-box-content{
            padding: 20px;
        }
        .full-width-link{
			width: 100%;
			text-align: center !important;
		}
        @media screen and (min-width: 640px) {
            .wrap .col-left{
                float:left;
                width: 70%;
            }
            .wrap .col-right{
                float:right;
                width: 26%;
            }
        }
    </style>

    <?php $url_logo = plugins_url('../img/admin-logo.png', __FILE__); ?>

    <div class="settings-page-heading">
        <h1><?php echo JGCABD_PLUGIN_NAME; ?></h1>
        <div class="author"><img src="<?php echo esc_url($url_logo); ?>"></div>
    </div>

    <div class="wrap">

        <div class="col-left">

            <h2><?php _e('Settings', 'jgc-adblocker-detector'); ?></h2><hr>

            <h2 class="nav-tab-wrapper">
    		<a href="#jgcabd-general" class="nav-tab"><?php _e('General', 'jgc-adblocker-detector'); ?></a>
    		<a href="#jgcabd-modal-box" class="nav-tab"><?php _e('Modal box', 'jgc-adblocker-detector'); ?></a>
    		<a href="#jgcabd-text-containers" class="nav-tab"><?php _e('Text containers', 'jgc-adblocker-detector'); ?></a>
			<a href="#jgcabd-ganalytics" class="nav-tab"><?php _e('Google Analytics', 'jgc-adblocker-detector'); ?></a>
    	    </h2>


            <form id="frm_jgcabd_opt" name="frm_jgcabd_opt" method="post" action="options.php" >

        		<?php
                settings_fields('jgcabd_options_group');
        		$jgcabd_options = get_option('jgcabd_options');
                ?>

                <!-- General tab -->
                <div id="jgcabd-general" class="jgcabd_content_tab">

                    <p><br><?php printf(__('%1$s allows you to display a notice when an ad blocker is detected. This notice can be displayed in a modal box or as text containers in the places where your ads usually appear. If you are not an advanced user of WordPress the recommended option is the modal box.', 'jgc-adblocker-detector'), 'JGC AdBlocker Detector'); ?></p>

                    <table class="form-table">
        				<tr>
        					<th scope="row"><?php _e('Enable plugin', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <p><input type="checkbox" name="jgcabd_options[enable_adblocker_detector]"
                                <?php echo checked( $jgcabd_options['enable_adblocker_detector'], 'on', false ); ?> />
                                <?php _e('Enable AdBlocker Detector', 'jgc-adblocker-detector'); ?></p>

								<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="jgcabd_options[disable_for_administrators]"
                                <?php echo checked( $jgcabd_options['disable_for_administrators'], 'on', false ); ?> />
                                <?php _e('Disable for administrators', 'jgc-adblocker-detector'); ?></p>
                            </td>
                        </tr>

                        <tr>
        					<th scope="row"><?php _e('Notice mode', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <select name="jgcabd_options[mode]" id="jgcabd_options[mode]" >
                                    <option value="modal-box" <?php echo selected($jgcabd_options['mode'], 'modal-box', false); ?>><?php _e('Modal box', 'jgc-adblocker-detector'); ?></option>

                                    <option value="text-containers" <?php echo selected($jgcabd_options['mode'], 'text-containers', false); ?>><?php _e('Text containers', 'jgc-adblocker-detector'); ?></option>
                                </select>
								<p class="description">(<?php _e('When you have selected the mode, go to the corresponding tab to set its settings.', 'jgc-adblocker-detector'); ?>)</p>
                            </td>
                        </tr>
                    </table>
                </div><!-- #jgcabd-general -->

                <!-- Modal box tab -->
                <div id="jgcabd-modal-box" class="jgcabd_content_tab">
                    <p><br><?php _e('Here you can set notice heading and text and if the modal box will show a button to close it.', 'jgc-adblocker-detector'); ?></p>
                    <table class="form-table">
                        <tr>
        					<th scope="row"><?php _e('Notice heading', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <input type="text" name="jgcabd_options[modal_box_heading]" value="<?php echo esc_attr($jgcabd_options['modal_box_heading']); ?>" size="60" />
                            </td>
                        </tr>

                        <?php
                        $settings_editor_1 = array(
        				'textarea_name' => 'jgcabd_options[modal_box_content]',
        				'editor_height' => 250,
                        'wpautop' => false,
                        'media_buttons' => false,
            			);
                        ?>
                        <tr>
        					<th scope="row"><?php _e('Notice text', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top"><?php
                    			wp_editor( $jgcabd_options['modal_box_content'], 'editor_modal_box_content', $settings_editor_1 );
            			        ?>
                            </td>
                        </tr>

                        <tr>
        					<th scope="row"><?php _e('Close button', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <select name="jgcabd_options[display_close_button]" >
                                    <option value="display" <?php echo selected($jgcabd_options['display_close_button'], 'display', false); ?>><?php _e('Display', 'jgc-adblocker-detector'); ?></option>

                                    <option value="not-display" <?php echo selected($jgcabd_options['display_close_button'], 'not-display', false); ?>><?php _e('Not display', 'jgc-adblocker-detector'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div><!-- #jgcabd-modal-box -->

                <!-- Text containers tab -->
                <div id="jgcabd-text-containers" class="jgcabd_content_tab">
                    <p><strong><?php _e('This option requires a minimum knowledge of the CSS of your WordPress theme.', 'jgc-adblocker-detector'); ?></strong></p>

                    <p><?php printf(__('%1$s will create a div container before the CSS selectors you indicate below to display your custom text.', 'jgc-adblocker-detector'), 'JGC AdBlocker Detector'); ?></p>

                    <p><?php _e('These CSS selectors should match those assigned to the containers in your ads.', 'jgc-adblocker-detector'); ?></p>
                    <table class="form-table">
                        <tr>
        					<th scope="row"><?php _e('CSS selectors (comma separated)', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <input type="text" name="jgcabd_options[ad_containers_css_selectors]" value="<?php echo esc_attr($jgcabd_options['ad_containers_css_selectors']); ?>" size="60" />
                                <p class="description"><?php _e("'class' or 'id' selectors of elements before which you want to display your custom text (comma separated).", 'jgc-adblocker-detector'); ?>.</p>
                                <p class="description">(<?php _e('Eg: #adsense, .hentry .ads, .sponsor', 'jgc-adblocker-detector'); ?>)</p>
                            </td>
                        </tr>

                        <?php
                        $settings_editor_2 = array(
        				'textarea_name' => 'jgcabd_options[text_containers_content]',
        				'editor_height' => 250,
                        'wpautop' => false,
                        'media_buttons' => false,
            			);
                        ?>
                        <tr>
        					<th scope="row"><?php _e('Text of containers', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top"><?php
                    			wp_editor( $jgcabd_options['text_containers_content'], 'editor_text_in_ad_container', $settings_editor_2 );
            			        ?>
                            </td>
                        </tr>
                    </table>
                </div><!-- #jgcabd-text-containers -->

				<!-- G. Analytics tab -->
                <div id="jgcabd-ganalytics" class="jgcabd_content_tab">
					<table class="form-table">
						<tr>
        					<th scope="row"><?php _e('Google Analytics event tracking', 'jgc-adblocker-detector'); ?></th>
        					<td valign="top">
                                <p><?php _e('Enable this option if your site already has a Google Analytics tracking code and you want to create an event to track the use of ad blockers.', 'jgc-adblocker-detector'); ?></p>

								<p><?php _e('Notice: The creation of this event will distort the bounce rate in Google Analytics because even if the visitor sees only one page, it will be interpreted as having seen two. The bounce rate will appear lower than it actually is.', 'jgc-adblocker-detector'); ?></p>

                                <p><input type="checkbox" name="jgcabd_options[enable_g_analytics_event]"
                                <?php echo checked( $jgcabd_options['enable_g_analytics_event'], 'on', false ); ?> />
                                <?php _e('Create Google Analytics event tracking for Ad Blockers', 'jgc-adblocker-detector'); ?></p>

                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(<?php printf(__('Event Category: %1$s | Event Action: %2$s', 'jgc-adblocker-detector'), 'AdBlock', 'AdBlock ON'); ?>)</i></p>
                            </td>
                        </tr>
					</table>
				</div><!-- #jgcabd-ganalytics -->

                <hr>

        		<p><input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'jgc-adblocker-detector' ); ?>" /></p>

            </form>
        </div><!-- .col-left -->

        <div class="col-right">
			<div class="info-box">
                <div class="info-box-heading"><?php _e('Links', 'jgc-adblocker-detector'); ?></div>
                <div class="info-box-content">
					<?php _e('Please, if you are happy with the plugin, say it on wordpress.org and give it a nice review. Thank you.', 'jgc-adblocker-detector'); ?>
					<p><a class="button-secondary full-width-link" href="https://wordpress.org/support/plugin/jgc-adblocker-detector/reviews/" target="_blank"><?php _e('Rate/Review', 'jgc-adblocker-detector'); ?></a></p>
					<hr>
					<p><a class="button-secondary full-width-link" href="https://wordpress.org/support/plugin/jgc-adblocker-detector" target="_blank"><?php _e('Support forum', 'jgc-adblocker-detector'); ?></a></p>

					<p><a class="button-secondary full-width-link" href="https://galussothemes.com/wordpress-themes" target="_blank"><?php _e('Our WordPress Themes', 'jgc-adblocker-detector'); ?></a></p>
                </div>
            </div><!-- .info-box -->
        </div><!-- .col-right -->

    </div><!-- wrap -->
    <?php
}
