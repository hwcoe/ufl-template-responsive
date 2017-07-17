//responsive main menu
jQuery(document).ready(function($) {
	$("#responsive-menu-toggle").click(function(){
		if ($(this).hasClass("closed")) {
			if ($("#responsive-grid").is(":visible")){
				$("#responsive-grid").css('display','none');
				$("#responsive-grid-toggle").addClass("closed");
			} else {
				$("#responsive-page-menu-toggle").addClass("closed");
		}

		// main navigation menu
		$("#primary-nav").addClass("pri-show");
		// //create span toggle in pri-show li a
		$(".pri-show>ul>li.parent>a").prepend('<span class="primary-nav-toggle">Toggle</span>');
		$('.primary-nav-toggle').click(function(e) {
				e.preventDefault();

				if ($("#primary-nav").hasClass("responsive-mega")){
					if( $(this).parent().parent(".sub").is(":hidden")) {
						$(this).parent().parent().find(".sub").stop().slideDown();
						$(this).addClass("flyout_toggle_active");
					} else {
						$(this).parent().parent().find(".sub").stop().slideUp();
						$(this).removeClass("flyout_toggle_active");
					}
				} else {
					if ($(this).parent().parent(".children").is(":hidden")) {
						$(this).parent().parent().find(".children").stop().slideDown();
						$(this).addClass("flyout_toggle_active");
					} else {
						$(this).parent().parent().find(".children").stop().slideUp();
						$(this).removeClass("flyout_toggle_active");
					}
				}
		});

			//$("#primary-nav").show();
			//$("#full-modal").show();
		$("#full-modal").css('z-index','9997');

		$(this).removeClass("closed");
		} else {
			$("#primary-nav").removeClass("pri-show");
			$( ".primary-nav-toggle" ).remove();
			$("#full-modal").css('z-index','-9999');
			$(this).addClass("closed"); 
		}
	});

//responsive icon menu
	$("#responsive-grid-toggle").click(function(){
		if ($(this).hasClass("closed")){
			 if($("#primary-nav").is(":visible")){
				$("#primary-nav").css('display','none').css("margin-left","0");
				$("#responsive-menu-toggle").addClass("closed");
			}
			$("#responsive-grid").fadeIn('fast');
			$(this).removeClass("closed");
			//$("#full-modal").show();
			$("#full-modal").css('z-index','9997');
		} else{
			$("#responsive-grid").fadeOut('fast');
			$(this).addClass("closed");
			$("#full-modal").css('z-index','-9999');
		}
	});

	
// responsive role based menu
	//create span toggle in user-role h3 a
	$("#user-role h3 a").prepend('<span class="user-role-toggle">Toggle</span>');
	//user role nav
	$('.user-role-toggle').click(function(e) {
		e.preventDefault();
		$(this).parent().parent().parent().find('ul').slideToggle();
		$(this).toggleClass('flyout_toggle_active');
	});

//close open menus
	$("#full-modal").click(function(){
	   if ($("#primary-nav").is(":visible")){
			$("#primary-nav").removeClass("pri-show");
			$( ".primary-nav-toggle" ).remove();
			$("#responsive-menu-toggle").addClass("closed");
			$("#full-modal").css('z-index','-9999');
		}
		if ($("#responsive-grid").is(":visible")){
			$("#responsive-grid").fadeOut("fast");
			$("#responsive-grid-toggle").addClass("closed");
			$("#full-modal").css('z-index','-9999');
		}

	});

});
function pageMenu(){
 if ( $(window).width() < 699) {
		$("#sidebar-nav").appendTo("#main-content");
	}
	else{
		$("#sidebar-nav").appendTo("#content");
		$("#primary-nav").css("display", "block");
	}
}

