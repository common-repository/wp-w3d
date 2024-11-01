<?php

class W3dSlider {

	public $type;
	public $content_type;
	public $data;

	function __construct( $arg_type = "simple_parallax", $arg_content_type = "static" ) {

		$this->type = $arg_type;
		$this->content_type = $arg_content_type;

	}

	public function getHtml() {

		//if ($this->content_type == "static") {
		//	return $this->getStaticContentHtml();
		//}

		include( W3D_ROOT_PATH . '/public/views/sliders/slider-init.php' );
		
		ob_start();
		if ($this->type == "simple_parallax") {
			include( W3D_ROOT_PATH . '/public/views/sliders/simpleparallaxslider-markup-html.php' );
		} elseif ($this->type == "mouse_parallax") {
			include( W3D_ROOT_PATH . '/public/views/sliders/mouseparallaxslider-markup-html.php' );
		} else {
			include( W3D_ROOT_PATH . '/public/views/sliders/3dboxslider-markup-html.php' );
		}

		return $buffer_content = apply_filters( 'slider_content', ob_get_clean() );

	}

}

?>