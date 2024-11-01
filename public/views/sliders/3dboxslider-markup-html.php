<?php
/**
 * Represents the front-end view for the 3D box slider.
 *
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */

// Start of slider wrapper
$html[] = '<div class="w3d-box-slider-wrapper"><ul id="sb-slider" class="sb-slider">';

// Loop over grid items
foreach ($data as $item) 
{
	// Start of item
	$html[] = '<li>';

	// Link & Image
	$html[] = '<a href="'.$item['url'].'" target="_blank"><img src="'.$item['image_url'].'" alt="image"/></a>';

	// Title
	$html[] = '<div class="sb-description"><h3>' . $item['title'] . '</h3></div>';

	// End of item
	$html[] = '</li>';
}

// End of slider wrapper
$html[] = '</ul><div id="shadow" class="shadow"></div><div id="nav-arrows" class="nav-arrows"><a href="#">Next</a><a href="#">Previous</a></div>';


/*
	<div id="nav-dots" class="nav-dots">
		<span class="nav-dot-current"></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
		<span></span>
	</div>
*/

// Output generated HTML
echo $html = implode('', $html);
?>
