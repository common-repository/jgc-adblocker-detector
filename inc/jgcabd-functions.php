<?php
function jgcabd_option($option) {

	$jgcabd_options = get_option( 'jgcabd_options' );
	$option_value = $jgcabd_options[$option];

	return $option_value;

}

function jgcabd_run(){

	if (jgcabd_option('enable_adblocker_detector') == 'on'){

		if (current_user_can('manage_options') && jgcabd_option('disable_for_administrators') == 'on'){
			return;
		}

		add_action('wp_enqueue_scripts', 'jgcabd_enqueue_scripts');
		add_action('wp_footer', 'jgcabd_wp_footer');

	}

}

function jgcabd_enqueue_scripts(){

	if (!wp_style_is('dashicons')) wp_enqueue_style('dashicons');

	wp_enqueue_script('jgcabd-detect-adblock', plugins_url('../js/jgcabd-detect-adblock.js', __FILE__), array('jquery'), JGCABD_PLUGIN_VERSION, true);

}

function jgcabd_wp_footer(){

	$jgcabd_opts = get_option( 'jgcabd_options');
	$mode        = $jgcabd_opts['mode'];

	if ($mode == 'modal-box'){
		$modal_box_heading    = wp_kses_post($jgcabd_opts['modal_box_heading']);
		$modal_box_content    = wp_kses_post($jgcabd_opts['modal_box_content']);
		$display_close_button = $jgcabd_opts['display_close_button'] == 'display' ? 'block' : 'none';
	}else{
		$ad_containers_css_selectors = '"' . $jgcabd_opts['ad_containers_css_selectors'] . '"';
		$text_containers_content     = wp_kses_post(trim($jgcabd_opts['text_containers_content']));

		$search = array('"', "'", "\n", "\r", "\n\r", "\t");
		$replace = array('\"', "\'", "", "", "","");
		$text_containers_content = '"<div class=\"jgcabd-text-container\">' . str_replace($search, $replace, $text_containers_content) . '</div>"';
	}

	$enable_g_analytics_event = $jgcabd_opts['enable_g_analytics_event'];

	if ($mode == 'modal-box') { ?>
		<style type="text/css">
			.jgcabd-container {
				display:none;
				position:fixed;
				left:0;
				top:0;
				height:100%;
				width: 100%;
				line-height: 1.7;
				font-size: 1rem;
				background-color: rgba(0, 0, 0, 0.8);
				z-index: 999999;
			}
			.jgcabd-notice-wrapper{
				max-width: 520px;
				margin:4rem auto 0;
				padding:1.5rem 3rem;
				border-radius: 0.5rem;
				background-color: white;
			}
			.jgcabd-notice-icon{
				text-align: center;
				padding-bottom: 2.5rem;
				line-height: 1;
			}
			.jgcabd-notice-icon .dashicons{
				font-size: 48px;
				color:#DD9933;
			}
			.jgcabd-notice-heading{
				text-align: center;
				font-size: 21px;
				font-weight: bold;
				padding-bottom: 1rem;
				border-bottom: 1px solid #ccc;
				margin-bottom: 1rem;
				color:#555;
			}
			.jgcabd-notice-text{
				color:#767676;
			}
			.jgcabd-notice-text p:not(:last-child){
				margin-bottom:1rem;
			}
			.jgcabd-wrapper-button{
				text-align: center;
				padding: 1.5rem 0 0;
				margin-top:1rem;
				border-top: 1px solid #ccc;
			}
			.jgcabd-close-button{
				display:<?php echo $display_close_button; ?>;
				font-size:16px;
				padding:0.5rem 2rem;
				border-radius: 4px;
				background-color: #9B9B9B;
				color:white;
				cursor: pointer;
			}
		</style>

		<div class="jgcabd-container">
			<div class="jgcabd-notice-wrapper">

				<div class="jgcabd-notice-icon">
					<span class="dashicons dashicons-info"></span>
				</div>

				<div class="jgcabd-notice-heading">
					<?php echo $modal_box_heading; ?>
				</div>

				<div class="jgcabd-notice-text">
					<?php echo $modal_box_content; ?>
				</div>

				<div class="jgcabd-wrapper-button">
					<span class="jgcabd-close-button"><?php _e('Close', 'jgc-adblocker-detector'); ?></span>
				</div>

			</div><!-- .jgcabd-notice-wrapper -->
		</div><!-- .jgcabd-container -->
	<?php } // if ($mode == 'modal-box') ?>

	<script type="text/javascript">

		function adBlockDetected(){

			<?php if ($mode == 'modal-box'){ ?>
                jQuery('.jgcabd-container').show('slow');
			<?php }else{ ?>
				jQuery(<?php echo $ad_containers_css_selectors; ?>).before(<?php echo $text_containers_content; ?>);
			<?php } ?>

			<?php if ($enable_g_analytics_event == 'on'){ ?>
				ga('send', {
                  'hitType': 'event',
                  'eventCategory': 'AdBlock',
                  'eventAction': 'AdBlock ON',
                });
			<?php } ?>
		}

		jQuery(document).ready(function(){
			var fuckAdBlock = new FuckAdBlock({
				checkOnLoad: true,
				resetOnEnd: true
			});

			fuckAdBlock.onDetected(adBlockDetected);

			jQuery('.jgcabd-close-button').click(function(){
                jQuery('.jgcabd-container').hide('slow');
			});

		});

	</script>

	<?php

} // jgcabd_wp_footer()
