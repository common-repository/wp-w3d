<?php 

// Start of grid container
$html[] = '<ul class="w3d-pages-grid type-' . $this->type . '">';

// Loop over grid items
foreach ($data as $item) 
{
	// Start of item
	$html[] = '<li><a href="' . $item['url'] . '">';

	// Image
	if ($this->type == "4" || $this->type == "5") {
		$html[] = '<div class="photo-wrapper"><figure class="photo">';
		$html[] = '<img src="' . $item['image_url'] . '" alt="01">';
	}
	else
	{
		$html[] = '<div class="w3d-pages-thumbnail" style="background-image:url(' . $item['image_url'] . ')"></div>';
	}

	// Title
	if ($this->type == "4" || $this->type == "5") { $html[] = '<figcaption>'; }
	$html[] = '<h3 class="w3d-pages-title">' . $item['title'] . '</h3>';
	if ($this->type == "4" || $this->type == "5") { $html[] = '</figcaption></figure>'; }

	// Content
	if ($this->type == "4" || $this->type == "5") { $html[] = '<div class="photo-back">'; }
	$html[] = '<p class="w3d-pages-excerpt">' . $item['content'] . '</p>';
	if ($this->type == "4" || $this->type == "5") { $html[] = '</div></div>'; }

	// End of item
	$html[] = '</a></li>';
}

// End of grid container
$html[] = '</ul>';

// Output generated HTML
echo $html = implode('', $html);

?>