<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */
function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = wp_get_theme();
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *	
 */
function optionsframework_options() {
	
	$parent_colleges_institutes = array(
		"University of Florida" => "University of Florida",
		"None" => "None"
	);
	
	// Pull all the categories into an array
	$options_categories = array("Choose a Category" => "Choose a Category");	
	$options_categories_obj = get_categories(array('hide_empty' => 0));
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =	get_bloginfo('stylesheet_directory') . '/library/images/';
		
	$options = array();
		
	$options[] = array( "name" => "General",
			"type" => "heading");
			
	$options[] = array( "name" => "Parent College / Institute",
			"desc" => "Select your parent organization.",
			"id" => "opt_parent_colleges_institutes",
			"std" => "one",
			"type" => "select",
			"options" => $parent_colleges_institutes);
					
	$options[] = array( "name" => "Google Analytics Account Number",
			"desc" => "Enter your account number for Google Analytics (e.g., 'UA-xxxxxxx-x' or 'UA-xxxxxxx-xx' )",
			"id" => "opt_analytics_acct",
			"std" => "",
			"type" => "text");
			
	$options[] = array( "name" => "Enable Mega Drop Down Menu",
			"desc" => "Enable mega drop down menus for your main menu",
			"id" => "opt_mega_menu",
			"std" => "0",
			"type" => "checkbox");
	
	$options[] = array( "name" => "Collapse Sidebar Navigation",
			"desc" => "Useful for larger sites - keeps the sidebar navigation a manageable height",
			"id" => "opt_collapse_sidebar_nav",
			"std" => "0",
			"type" => "checkbox");
			
	$options[] = array( "name" => "Shibboleth Protocol",
			"desc" => "Select the protocol you have Shibboleth enabled on.",
			"id" => "opt_shibboleth_protocol",
			"std" => "https",
			"type" => "select",
			"options" => array(
					"http" => "http",
					"https" => "https")
			);

	$options[] = array( "name" => "Responsive Theme",
			"desc" => "Enables a responsive theme for your site.",
			"id" => "opt_responsive",
			"std" => "1",
			"type" => "checkbox");

	// Site Title 
	$options[] = array( "name" => "Header",
			"desc" => "Options for modifying the header. The below options modify the social media icons.",
			"type" => "heading");

	$options[] = array( "name" => "Facebook",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's Facebook page (default: https://www.facebook.com/ufwertheim/)",
			"id" => "opt_facebook_url",
			"std" => "",
			"type" => "text");
			
	$options[] = array( "name" => "Twitter",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's Twitter page (default: https://twitter.com/ufwertheim/)",
			"id" => "opt_twitter_url",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Youtube",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's Youtube page (default: https://www.youtube.com/user/gatorengineering)",
			"id" => "opt_youtube_url",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "LinkedIn",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's LinkedIn profile (default: https://www.linkedin.com/edu/school?id=193009)",
			"id" => "opt_linkedin_url",
			"std" => "",
			"type" => "text");
		
	$options[] = array( "name" => "Instagram",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's Instagram profile (default: https://instagram.com/ufwertheim/)",
			"id" => "opt_instagram_url",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Flickr",
			"super-admin-only" => "1",
			"desc" => "Enter the url of your organization's Flickr profile (default: https://www.flickr.com/photos/gatorengineering/)",
			"id" => "opt_flickr_url",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Facebook Insights ID",
			"super-admin-only" => "1",
			"desc" => "Enter the unique number ID for fb:admins, e.g., <meta property=\"fb:admins\" content=\"1138099648\", would be \"1138099648\" />",
			"id" => "opt_facebook_insights",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Search Options",
			"desc" => "These options modify the behavior of the search box in the header.",
			"type" => "info");
	
	$options[] = array( "name" => "Site Specific Search",
			"desc" => "Only search this site with UF Search (this option sets the site:example.ufl.edu search option)",
			"id" => "opt_site_specific_search",
			"std" => "1",
			"type" => "checkbox");
	 
	$options[] = array( "name" => "Use WordPress Search",
			"desc" => "Use WordPress Search instead of the default UF Search site. This option ignores the Site Specific Search option as WordPress search can only search this site",
			"id" => "opt_use_wp_search",
			"std" => "0",
			"type" => "checkbox");
	 
	$options[] = array( "name" => "Search Box Text",
			"desc" => "Change the default text of the search box.",
			"id" => "opt_search_box_text",
			"std" => "Search this site",
			"type" => "text");

	$options[] = array( "name" => "Site Title",
			"desc" => "These options adjust the sizing of the site title.",
			"type" => "info");

	$options[] = array( "name" => "Title Font Size",
			"desc" => "Enter a number that corresponds to the size of the font you would like for the title of your site (Default 2.2).",
			"id" => "opt_title_size",
			"class" => "mini",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Title Padding",
			"desc" => "Enter the amount of padding the title should have (Default 6).",
			"id" => "opt_title_pad",
			"class" => "mini",
			"std" => "",
			"type" => "text");	 
			
	$options[] = array( "name" => "Tagline Font Size",
			"desc" => "Enter a number that corresponds to the size of the font you would like for the tagline of your site (Default 1.2).",
			"id" => "opt_tagline_size",
			"class" => "mini",
			"std" => "",
			"type" => "text");					

	// Call to Action Button
	$options[] = array( "name" => "Call to Action",
			"type" => "heading");
			
	$options[] = array( "name" => "Call to Action Text",
			"desc" => "The Call to Action text is the orange box above your main menu. Enter what you would like it to say here. Leave it blank to remove it.",
			"id" => "opt_actionitem_text",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Call to Action URL",
			"desc" => "Where visitors are taken when they click on your Header Action Item",
			"id" => "opt_actionitem_url",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Call to Action Alternate Color",
			"desc" => "This is an alternate color (red) used for warnings and emergency alerts.",
			"id" => "opt_actionitem_altcolor",
			"std" => "0",
			"type" => "checkbox");

	// Homepage Layout
	$options[] = array( "name" => "Homepage",
			"desc" => "Options for modifying the homepage.",
			"type" => "heading");

	$options[] = array( "name" => "Meta description",
			"desc" => "Enter the meta description for your home page.",
			"id" => "opt_meta_description",
			"std" => "",
			"type" => "textarea");

	$options[] = array( "name" => "Feature Slider",
			"desc" => "These options edit the featured slider.",
			"type" => "info");
		 
	$options[] = array( "name" => "Select a Category",
			"desc" => "Choose a category from which featured posts are drawn. To remove the featured content area, simply set this dropdown to 'Choose a Category'",
			"id" => "opt_featured_category",
			"type" => "select",
			"std" => array("Choose a Category" => "Choose a Category"),
			"options" => $options_categories);
			
	$options[] = array( "name" => "Number of Posts to Display in Slider",
			"desc" => "How many posts do you want to display in your slider (Story Stacker is fixed at 3)",
			"id" => "opt_number_of_posts_to_show",
			"std" => "3",
			"type" => "select",
			"class" => "mini",
			"options" => array("1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5", "6" => "6", "7" => "7", "8" => "8", "9" => "9", "10" => "10", "11" => "11", "12" => "12", "13" => "13", "14" => "14", "15" => "15"));
				
	$options[] = array( "name" => "Slider Speed", 
			"desc" => "Time in milliseconds to display each slide (e.g. 5000 means five seconds)", 
			"id" => "opt_featured_speed", 
			"std" => "7000", 
			"type" => "text"); 
		
	$options[] = array( "name" => "Disable Timeline Scrubber",
			"desc" => "Disable the long bar with dots underneath the images",
			"id" => "opt_featured_content_disable_timeline",
			"std" => "0",
			"type" => "checkbox");
		
	$options[] = array( "name" => "Disable Slider Links",
			"desc" => "Disable links from being created on the homepage slider.",
			"id" => "opt_featured_disable_link",
			"std" => "0",
			"type" => "checkbox");
			
	$options[] = array( "name" => "Story Stacker",
			"desc" => "Check to enable the Featured Content Story Stacker for your home page.",
			"id" => "opt_story_stacker",
			"std" => "0",
			"type" => "checkbox");
		 
	$options[] = array( "name" => "Story Stacker - Disable Dates",
			"desc" => "Disable dates from appearing underneath your post titles",
			"id" => "opt_story_stacker_disable_dates",
			"std" => "0",
			"type" => "checkbox");

	$options[] = array( "name" => "Homepage Layout",
			"desc" => "These options modify the layout of the homepage widget areas.",
			"type" => "info");

	$options[] = array( "name" => "Homepage Layout for Widgets",
			"desc" => "Select which layout you want to use for your widgets on the homepage",
			"id" => "opt_homepage_layout",
			"std" => "2c-bias",
			"type" => "images",
			"options" => array(
				'3c-default' => $imagepath . '3c-default.png',
				'3c-thirds' => $imagepath . '3c-thirds.png',
				'2c-bias' => $imagepath . '2c-bias.png',
				'2c-half' => $imagepath . '2c-half.png',
				'1c-100' => $imagepath . '1c-100.png',
				'1c-100-2c-half' => $imagepath . '1c-100-2c-half.png')
			);
	$options[] = array( "name" => "Featurebox Widget Area Visibility",
		"desc" => "Show featurebox widget area in the footer of the home page",
		"id" => "opt_featurebox_widgets_visibility",
		"std" => "0",
		"type" => "checkbox");
	
	$options[] = array( "name" => "Color Scheme (white background)",
			"desc" => "Use a white background for the homepage widget zone",
			"id" => "opt_homepage_layout_color",
			"std" => "0",
			"type" => "checkbox");

	// Footer Options
	$options[] = array( "name" => "Footer",
		"type" => "heading");

	$options[] = array( "name" => "Intranet URL",
			"desc" => "Enter the URL to your unit's intranet. This will place a link at the bottom of the footer titled 'Intranet'",
			"id" => "opt_intranet_url",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Make a Gift URL",
			"desc" => "Enter the URL to your unit's specific fund/giving page at the UF Foundation. Find available online funds at the <a href='https://www.uff.ufl.edu/OnlineGiving/Advanced.asp'>UF Foundation</a>",
			"id" => "opt_makeagift_url",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Footer Widget Visibility",
			"desc" => "Select where to show the Footer Widgets",
			"id" => "opt_footer_widgets_visibility",
			"std" => "all_pages",
			"type" => "radio",
			"options" => array(
			 'all_pages' => 'All Pages (including Homepage)',
			 'homepage_only' => 'Homepage Only',
			 'subpages_only' => 'Subpages Only')
			);

	$options[] = array( "name" => "Contact Information",
		"desc" => "Enter your unit's contact information (defaults to College of Engineering contact info).",
		"type" => "info");

	$options[] = array( "name" => "Contact Name",
			"desc" => "Enter the name of your office or unit.",
			"id" => "opt_contact_name",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Physical Address",
			"desc" => "Enter the physical address of your office or unit.",
			"id" => "opt_contact_physical",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "P.O. Box",
			"desc" => "Enter the P.O. Box Number of your office or unit.",
			"id" => "opt_contact_postal",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "City, State, and ZIP code",
			"desc" => "Enter the City, State, and ZIP code of your office or unit.",
			"id" => "opt_contact_city_state_zip",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Phone Number",
			"desc" => "Enter the phone number of your office or unit.",
			"id" => "opt_contact_phone",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Fax Number",
			"desc" => "Enter the fax number of your office or unit.",
			"id" => "opt_contact_fax",
			"std" => "",
			"type" => "text");

	$options[] = array( "name" => "Email",
			"desc" => "Enter your unit's email address for informational contact requests (e.g. info@eng.ufl.edu).",
			"id" => "opt_contact_email",
			"std" => "",
			"type" => "text");
	
	$options[] = array( "name" => "Webmaster Email",
			"desc" => "Enter the email address or contact page URL for webmaster contact requests (e.g. webmaster@yourdomain.edu OR http://yourdomain.edu/contact) ",
			"id" => "opt_webmaster_email",
			"std" => "",
			"type" => "text");
		


	//	Custom Attributes
	$options[] = array( "name" => "Custom Attributes",
			"type" => "heading");

	$options[] = array( "name" => "Custom CSS",
			"desc" => "Enter custom CSS to be inserted in the header.",
			"id" => "opt_custom_css",
			"std" => "",
			"type" => "textarea"); 

	$options[] = array( "name" => "Custom JS",
			"desc" => "Enter custom JS to be inserted in the footer.",
			"id" => "opt_custom_js",
			"std" => "",
			"type" => "textarea"); 
	
	$options[] = array( "name" => "Custom Mobile CSS",
			"desc" => "Enter custom CSS to be inserted in the header that will be used only if the Responsive template is viewed on a mobile device.",
			"id" => "opt_responsive_css",
			"std" => "",
			"type" => "textarea"); 

	$options[] = array( "name" => "Custom Logo",
			"desc" => "These options replace the logo in the header with an image.",
			"type" => "info");

	$options[] = array( "name" => "Custom Logo File",
			"super-admin-only" => "1",
			"desc" => "For advanced use only.",
			"id" => "opt_alternative_site_logo",
			"type" => "upload");		

	$options[] = array( "name" => "Custom Logo Height",
			"super-admin-only" => "1",
			"desc" => "For advanced use only. Enter height in pixels (i.e. 70)",
			"id" => "opt_alternative_site_logo_height",
			"class" => "mini",
			"std" => "",
			"type" => "text");	

	$options[] = array( "name" => "Custom Logo Width",
			"super-admin-only" => "1",
			"desc" => "For advanced use only. Enter width in pixels (i.e. 70)",
			"id" => "opt_alternative_site_logo_width",
			"class" => "mini",
			"std" => "",
			"type" => "text"); 

	return $options;
}
