<?php
/**
 * Represents the front-end view for the simple parallax slider.
 *
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */

// Start of slider wrapper
$html[] = '<div class="w3d-simple-prlx-slider-wrapper"><div id="w3d-prlx-slider" class="w3d-prlx-slider">';

// Loop over grid items
foreach ($data as $item) 
{
	// Start of item
	$html[] = '<div class="w3d-prlx-slide scale">';

	// Title
	$html[] = '<h2>' . $item['title'] . '</h2>';

	// Content
	$html[] = '<p>' . $item['content'] . '</p>';

	// Link
	$html[] = '<a href="'.$item['url'].'" class="w3d-prlx-link">' ._x('Read more...', 'Slider read more link text').'</a>';

	// Image
	$html[] = '<div class="w3d-prlx-img"><img src="' . $item['image_url'] . '" alt="image" /></div>';

	// End of item
	$html[] = '</div>';
}

// End of slider wrapper
$html[] = '<nav class="w3d-prlx-arrows"><span class="w3d-prlx-arrows-prev"></span><span class="w3d-prlx-arrows-next"></span></nav></div></div>';

// Output generated HTML
echo $html = implode('', $html);

?>