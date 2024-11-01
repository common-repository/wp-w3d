(function ( $ ) {
	"use strict";

	$(function () {

		// Edit slider administration-specific JavaScript here
		
		displayMetaBox(); // Onload

		$('#_w3dslider_type').change(function(e){
			var val = this.value;
			console.log(val);
			displayMetaBox(val);		
		});

		function displayMetaBox(slider_type){
			var val;
			if ( typeof(slider_type) == "undefined" )
			{
				val = $('#_w3dslider_type').val();
				console.log("1 : " + val);
			}
			else
			{
				val = slider_type;
				console.log("2 : " + val);
			}
			
			if (val == "simple_parallax") 
			{
				$('#w3d_slider_advanced_settings_box_simple_parallax').show();
				$('#w3d_slider_advanced_settings_box_mouse_parallax').hide();
				$('#w3d_slider_advanced_settings_box_3d_box').hide();
			}
			else if (val == "mouse_parallax")
			{
				$('#w3d_slider_advanced_settings_box_simple_parallax').hide();
				$('#w3d_slider_advanced_settings_box_mouse_parallax').show();
				$('#w3d_slider_advanced_settings_box_3d_box').hide();
			}
			else
			{
				$('#w3d_slider_advanced_settings_box_simple_parallax').hide();
				$('#w3d_slider_advanced_settings_box_mouse_parallax').hide();
				$('#w3d_slider_advanced_settings_box_3d_box').show();
			}

		}

	});

}(jQuery));