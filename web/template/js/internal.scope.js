// setTimeout(function () {
//   window.scrollTo(0, 1);
// }, 1000);

$(document).ready(function() {
	// External links handler
	$('.external').on('click', function(e) {
		window.open($(this).attr('href'));
		e.preventDefault();
	});

	// Label tag toggles associated checkbox
	// (for browsers that do not support label click-through to form element)
	$("label").click( function() {
		$("#" + $(this).attr('for')).each( function() {
			$(this).attr('checked', !$(this).attr('checked'));
		});
		return false;
	});

	if (getCookie('anim') == 0) {
		$('#stopAnim').attr('checked', true);
	}

	$('#stopAnim').on('change', function() {

		if ($(this).attr('checked')) {
			// disable animations
			setCookie('anim', 0, 30);
		}
		else {
			// enable animations
			setCookie('anim', 1, 30);
		}
		// console.log(getCookie('anim'));
	});

	$('.n-main .nav-title').click(function() {
		$('.n-main').toggleClass('is-active');
		return false;
	});

	// $('.m-workshops .info:visible').hide();

	$('.m-workshops').localScroll({'hash':true});
/*
	$('.m-workshops .image').click(function () {
		anchor = $(this).attr('href');
		scrollToAnchor(anchor);
	});
*/
});


