<?php
/* -------------------------------------------------------------------------------------------- */
/* 	DO NOT COPY/PASTE BETWEEN TEST AND PRODUCTION SERVERS without checking and editing form IDs
/*	Form IDs are often different between sites and filters/actions won't work!
/* -------------------------------------------------------------------------------------------- */

// Set options for multi select fields
function set_chosen_options($form){
	?>
	
	<script type="text/javascript">
		gform.addFilter('gform_chosen_options','set_chosen_options_js');
		//limit how many options may be chosen in a multi-select
		function set_chosen_options_js(options, element){
			//form id = 25, field id = 39
			// graduate student affairs site, University of Puerto Rico Mayaguez SHPE ENGINE Participation form
			// if (element.attr('id') == 'input_48_39'){
			if (element.attr('class') == 'limit_options_to_2'){
				//limit number of options to 2 for "Graduate Program of Interest" field
				options.max_selected_options = 2;   
			}
			
			return options;
		}
	</script>
	
	<?php
	//return the form object from the php hook  
	return $form;
}

add_action("gform_pre_render", "set_chosen_options");

// Gravity Forms maintenance mode - uncomment filter to use
function maintenance_mode( $form_string, $form ) {
    $form_string = '<p>Forms have been temporarily disabled for scheduled maintenance. Please check back later.</p>';

    return $form_string;
}
// add_filter( 'gform_get_form_filter', 'maintenance_mode', 10, 2 );

?>