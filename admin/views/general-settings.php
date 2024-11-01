<?php
/**
 * Represents the view for the administration dashboard (Setttings page).
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */
?>

<div class="wrap">
<?php /* ?>
	<div id="message" class="updated below-h2">
		<p>
			<div class="dashicons dashicons-info"></div>&nbsp;<strong><?php _e('Hey! Go premium: ', 'wp-w3d') ?> </strong> <?php _e('If you want to know more about premium features you can check ', 'wp-w3d') ?>  <a href="http://wordpress.mba-multimedia.com/plugins/wp-w3d-plugin/" target="_blank"><?php _e('this page', 'wp-w3d') ?></a>
		</p>
	</div>
<?php */ ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- @TODO: Provide markup for your options page here. -->
	<?php 
		
		echo "<p>These W3D WP Plugin settings will only be available for premium users.</p>"

	?>
</div>