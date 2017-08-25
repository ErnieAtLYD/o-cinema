// FRONT PAGE SLIDE SHOW
// execute your scripts when the DOM is ready. this is mostly a good habit
$(function() {

	currentpos = 0;
	
	totalimages = $("#panel .item").size();
	sliderwidth = 0;
	
	// console.log('total', totalimages);
	sliderwidth = 186 * totalimages;
	
	// console.log("sliderwidth",sliderwidth);
	$("#panel").css("width",(sliderwidth)+"px");

	function variablechange(){
		// browserwidth = $(window).width();
		// console.log("browserwidth", browserwidth);
		panelwidth = $(".slider").width() - 175; // Total large image width, minus width of the right navigation arrow
		
		if (!currentpos) { 
			$('.leftnav').addClass('disabled');
			currentpos = 0;
		} else {
			$('.leftnav').removeClass('disabled');
		}

		if (currentpos + panelwidth > sliderwidth ) {
			$('.rightnav').addClass('disabled');
		} else {
			$('.rightnav').removeClass('disabled');
		}		
	}

	variablechange(); //for initialization before window resize	is called
	$(window).resize(function() { variablechange(); });
	
	$(document).on("click", ".rightnav", function(){
		// console.log('sliderwidth', sliderwidth);
		// console.log('panelwidth', panelwidth);
		// console.log('old currentpos', currentpos);
				
		currentpos = ((currentpos + panelwidth + panelwidth) > sliderwidth) 
						? sliderwidth - panelwidth
						: currentpos + panelwidth;
		
		$('.slider').animate( {scrollLeft: currentpos }, 750);
		// console.log('currentpos', currentpos);

		if (currentpos) { 
			$('.leftnav').removeClass('disabled');
		}	
		if (currentpos + panelwidth >= sliderwidth ) {
			$('.rightnav').addClass('disabled');
		}
					
	});

	$(document).on("click", ".leftnav", function(){
		// console.log('sliderwidth', sliderwidth);
		// console.log('browserwidth', browserwidth);

		currentpos = currentpos - panelwidth;
		if (currentpos < 0) currentpos = 0;
		
		$('.slider').animate( {scrollLeft: currentpos }, 750);
		// console.log('currentpos', currentpos);

		if (!currentpos) { 
			$('.leftnav').addClass('disabled');
		}	
		if (currentpos + panelwidth <= sliderwidth ) {
			$('.rightnav').removeClass('disabled');
		}			
	});
	
});