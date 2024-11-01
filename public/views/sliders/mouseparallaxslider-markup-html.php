<?php
/**
 * Represents the front-end view for the mouse parallax slider.
 *
 *
 * @package   WP_W3D
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 MBA Multimedia
 */
?>

<!-- Parallax slider markup -->

<?php 

// @TODO
// Get the slider ID
// Get the general slider settings
// Get the specific slider settings
// Print markup
?>
<div class="w3d-mouse-prlx-slider-wrapper">

	<div id="w3d-mouse-prlx-slider" class="w3d-mouse-prlx-slider">
		<div class="w3d-mouse-prlx-slide scene parallax-viewport">
			<div class="parallax-layer" data-depth="1"></div>
			<div class="parallax-layer" data-depth="0.6"></div>
			<div class="parallax-layer" data-depth="0.3"></div>
			<div class="slide-content">
				<h2>Slide 1 title...</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus, facere excepturi dolorum cupiditate sit praesentium quaerat sapiente voluptatem architecto mollitia ut ipsa inventore fuga quas odio illo natus corrupti commodi.</p>
				<a href="#TODO" class="w3d-mouse-prlx-link"><?php echo __('Read more...'); ?></a>
			</div>
		</div>
		<div class="w3d-mouse-prlx-slide scene">
			<div class="parallax-layer" data-depth="1"></div>
			<div class="parallax-layer" data-depth="0.8"></div>
			<div class="parallax-layer" data-depth="0.6"></div>
			<div class="parallax-layer" data-depth="0.4"></div>
			<div class="slide-content">
				<h2>Slide 2 title...</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, fugit, hic, qui, eaque dicta magnam unde velit eligendi non praesentium dignissimos laborum earum asperiores ipsam ut laboriosam rem est odio?</p>
				<a href="#TODO" class="w3d-mouse-prlx-link"><?php echo __('Read more...'); ?></a>
			</div>
		</div>
		<div class="w3d-mouse-prlx-slide scene">
			<div class="parallax-layer" data-depth="1"></div>
			<div class="parallax-layer" data-depth="0.6"></div>
			<div class="parallax-layer" data-depth="0.3"></div>
			<div class="slide-content">
				<h2>Slide 3 title...</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, dolorem dolor nisi ipsum est ipsa eius vero odit deleniti non tempora vel? Voluptatem, libero iure dolorem dolor voluptatibus labore pariatur!</p>
				<a href="#TODO" class="w3d-mouse-prlx-link"><?php echo __('Read more...'); ?></a>
			</div>
		</div>
		<nav class="w3d-mouse-prlx-arrows">
			<span class="w3d-mouse-prlx-arrows-prev"></span>
			<span class="w3d-mouse-prlx-arrows-next"></span>
		</nav>
	</div>

</div>