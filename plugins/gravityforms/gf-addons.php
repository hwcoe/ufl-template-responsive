<?php
/* ----------------------------------------------------------------------------------- */
/* 	DO NOT COPY/PASTE BETWEEN TEST AND PRODUCTION SERVERS without checking form IDs
/*	Form IDs are often different between sites and filters/actions won't work!
/* ----------------------------------------------------------------------------------- */

// Set options for multi select fields
function set_chosen_options($form){
	?>
	
	<script type="text/javascript">
		gform.addFilter('gform_chosen_options','set_chosen_options_js');
		//limit how many options may be chosen in a multi-select
		function set_chosen_options_js(options, element){
			//form id = 25, field id = 39
			// graduate student affairs site, University of Puerto Rico Mayaguez SHPE ENGINE Participation form
			if (element.attr('id') == 'input_25_39'){
				//limit number of options to 2
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

?>