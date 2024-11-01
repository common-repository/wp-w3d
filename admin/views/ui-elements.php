<?php
/**
 * Represents the view for the administration dashboard (UI Elements page).
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
			<div class="dashicons dashicons-info"></div>&nbsp;<strong><?php _e('Hey! Go premium: ', 'wp-w3d') ?> </strong> <?php _e('If you want to use premium UI elements youcan check ', 'wp-w3d') ?>  <a href="http://wordpress.mba-multimedia.com/plugins/wp-w3d-plugin/" target="_blank"><?php _e('this page', 'wp-w3d') ?></a>
		</p>
	</div>
<?php */ ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- @TODO: Provide markup for your options page here. -->
	<?php 
		
		echo "<p>This page will help you to browse all UI elements provided by the W3D Plugin and see how-to customize them and how-to add them in your pages through dedicated shortcodes !</p>"

	?>

	<h2 class="nav-tab-wrapper">
		<a href="#grids" class="nav-tab nav-tab-active"><?php _e('Grids', 'wp-w3d'); ?></a>
		<a href="#go-premium" class="nav-tab"><?php _e('Premium UI Elements (Soon)', 'wp-w3d'); ?></a>
	</h2>

	<!-- Tab 1 -->
	<?php include_once('ui-elements-tabs/grids.php'); ?>

	<!-- Tab 2 -->
	<?php // include_once('ui-elements-tabs/go-premium.php'); ?>

</div>