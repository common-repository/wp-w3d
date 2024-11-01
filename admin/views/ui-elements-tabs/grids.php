<p><?php _e('Grid elements can be used in pages to create a list of subpages or a mosaic of posts. Sections below will help you to find your perfect UI layout and discover how to customize it.', 'wp-w3d'); ?></p>

<p><em><?php _e('Note: screenshot have been made using Tweenty thirteen Wordpress theme', 'wp-w3d'); ?></em></p>

<h3>3 columns responsive grid</h3>

<figure class="w3d-admin-figure">
	<img class="w3d-admin-img" src="<?php echo W3D_ROOT_URL . "/admin/assets/img/grid-1.jpg" ?>" alt="grid type 1" />
	<figcaption>Type 1 - Scale effect on hover</figcaption>
</figure>

<figure class="w3d-admin-figure">
	<img class="w3d-admin-img" src="<?php echo W3D_ROOT_URL . "/admin/assets/img/grid-2.jpg" ?>" alt="grid type 2" />
	<figcaption>Type 2 - 3D left rotation</figcaption>
</figure>

<figure class="w3d-admin-figure">
	<img class="w3d-admin-img" src="<?php echo W3D_ROOT_URL . "/admin/assets/img/grid-3.jpg" ?>" alt="grid type 3" />
	<figcaption>Type 3 - 3D bottom rotation</figcaption>
</figure>

<figure class="w3d-admin-figure">
	<img class="w3d-admin-img" src="<?php echo W3D_ROOT_URL . "/admin/assets/img/grid-4.jpg" ?>" alt="grid type 4 (CSS) / 5 (JS)" />
	<figcaption>Type 4 &amp; 5 - 3D Flip polaroid</figcaption>
</figure>

<figure class="w3d-admin-figure">
	<img class="w3d-admin-img" src="<?php echo W3D_ROOT_URL . "/admin/assets/img/grid-6.jpg" ?>" alt="grid type 6" />
	<figcaption>Type 6 - CSS Filters <em>(Webkit only)</em></figcaption>
</figure>

<div style="clear: both;"></div>

<h4>Shortcode examples</h4>

<pre>[responsive3dgrid]</pre>
<pre>[responsive3dgrid type="1" id="1,69"]</pre>
<pre>[responsive3dgrid type="2" content="static"]</pre>
<pre>[responsive3dgrid type="3" include="1,92"]</pre>
<pre>[responsive3dgrid type="4" content="page" exclude="92" order="ASC"]</pre>

<h4><?php _e('Available parameters and values (Lite version)', 'wp-w3d'); ?></h4>

<table class="widefat">
	<thead>
		<th><?php _e('Parameter', 'wp-w3d'); ?></th>
		<th><?php _e('Desc.', 'wp-w3d'); ?></th>
		<th><?php _e('Values', 'wp-w3d'); ?></th>
		<th><?php _e('Default', 'wp-w3d'); ?></th>
	</thead>
	<tr>
		<td>type</td>
		<td>Chose CSS layouts and animation effects</td>
		<td>1 | 2 | 3 | 4 | 5 | 6 </td>
		<td>1</td>
	</tr>
	<tr class="alternate">
		<td>content</td>
		<td>Define which WP content sould be queried and listed (The static mode is explained below). Subpage will limit the query to children pages, depending on where you include the shortcode (Must be a page)</td>
		<td>post | page | subpage | static</td>
		<td>post</td>
	</tr>
	<tr>
		<td>include</td>
		<td>Comma separated list of Wordpress posts or pages IDs to filter the query (Not useful for static content!)</td>
		<td>eg. 1,2,4,5,10</td>
		<td>empty : all elements of the chosen content type will be retrieved from the database</td>
	</tr>
	<tr>
		<td>exclude</td>
		<td>Comma separated list of Wordpress posts or pages IDs to exclude from the query (Not useful for static content!)</td>
		<td>eg. 1,2,4,5,10</td>
		<td>empty : all elements of the chosen content type will be retrieved from the database</td>
	</tr>
	<tr>
		<td>order</td>
		<td>Designates the ascending or descending order of the queried elements</td>
		<td>ASC | DESC</td>
		<td>DESC : descending order from highest to lowest values</td>
	</tr>
	
</table>

<p class="help">The static mode allows user to define its own content in a file without querying the database. The content can be changed in the file located here (This file can be overwritten by a theme file) : <pre>plugin_dir/wp-w3d/public/views/ui-elements/grids/static-content/sample-content.php</pre></p>

<!--h3>Type X</h3>

<h4>Shortcode</h4>

<pre>[responsive3dgrid type="X"]</pre>

<h4><?php _e('Available parameters and values (Lite version)', 'wp-w3d'); ?></h4>

<table class="widefat">
	<thead>
		<th><?php _e('Parameter', 'wp-w3d'); ?></th>
		<th><?php _e('Desc.', 'wp-w3d'); ?></th>
		<th><?php _e('Values', 'wp-w3d'); ?></th>
	</thead>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="alternate">
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr class="alternate">
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>

<h4><?php _e('Premium options', 'wp-w3d'); ?></h4-->