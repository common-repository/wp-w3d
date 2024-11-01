<?php

class W3dResponsiveGrid {

	public $type;
	public $content_type;
	public $include;
	public $exclude;
	public $order;
	public $limit;

	private $data;

	function __construct( $arg_type, $arg_content_type, $arg_include, $arg_exclude, $arg_order, $arg_limit = '12' ) {

		$this->type = $arg_type;
		$this->content_type = $arg_content_type;
		$this->include = $arg_include;
		$this->exclude = $arg_exclude;
		$this->order = $arg_order;
		$this->limit = $arg_limit;

	}

	public function getHtml() {

		if ($this->type == "samples") {
			return $this->getSamplesHtml();
		}

		include( W3D_ROOT_PATH . '/public/views/ui-elements/grids/responsive3dgrid-init.php' );
		
		ob_start();
		include( W3D_ROOT_PATH . '/public/views/ui-elements/grids/responsive3dgrid-markup-html.php' );
		return $buffer_content = apply_filters( 'responsive_grid_content', ob_get_clean() );

	}

	private function getSamplesHtml() {

		ob_start();
		include( W3D_ROOT_PATH . '/public/views/ui-elements/grids/responsive3dgrid-samples.php' );
		return $buffer_content = ob_get_clean();

	}
}

?>