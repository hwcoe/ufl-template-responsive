jQuery(document).ready(function($) {
		  
	// PrettyPhoto
	function ufandshands_lightbox() {

	  //$("a[rel^='prettyPhoto'],.gallery a").prettyPhoto({
	  $("a[href$='.jpg'], a[href$='.jpeg'], a[href$='.gif'], a[href$='.png']").prettyPhoto({
		animationSpeed:'fast',
		slideshow:5000,
		theme:'pp_default',
		show_title:false,
		social_tools:false,
		overlay_gallery: true,
		markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
							<div class="pp_right"> \
								<div class="pp_content"> \
									<div class="pp_loaderIcon"></div> \
									<div class="pp_fade"> \
										<a class="pp_close" href="#">Close</a> \
										<div class="pp_hoverContainer"> \
											<a class="pp_next" href="#">next</a> \
											<a class="pp_previous" href="#">previous</a> \
										</div> \
										<div id="pp_full_res"></div> \
										<div class="pp_details"> \
											<div class="pp_nav"> \
												<a href="#" class="pp_arrow_previous">Previous</a> \
												<p class="currentTextHolder">0/0</p> \
												<a href="#" class="pp_arrow_next">Next</a> \
											</div> \
											<p class="pp_description"></p> \
											{pp_social} \
					  <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
										</div> \
									</div> \
								</div> \
							</div> \
							</div> \
						</div> \
						<div class="pp_bottom"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
					</div> \
					<div class="pp_overlay"></div>'
	  });

	  // pass image title to its parent anchor in order to display in lightbox
		$('#main-content a img').each(function(key,$elem){
		    $(this).parent().attr('title',$(this).attr('title'));
		});
	}

	if(jQuery().prettyPhoto) {

	  ufandshands_lightbox(); 

	}
	 
});

 // $(document).ready(function() {
jQuery(document).ready(function($) {
	$("#main-content table:not(.no-row-js) tbody").each(function() {
		$(this).find("tr:odd").addClass("even"); // 0-based indexing means :odd selects elements 2, 4, 6...
	});
});

