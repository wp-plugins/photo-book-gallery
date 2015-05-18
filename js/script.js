jQuery(document).ready(function($) {

	if (book.width == '') {
		bookWidth = '100%';
	}
	else {
		bookWidth = book.width * 2;
	}

	if (book.height == '') {
		bookHeight = 400;
	}
	else {
		bookHeight = book.height;
	}

	var wcpAuto = false;
	var wcpDelay = 2000;
	if(book.delay != ''){
		wcpAuto = true;
		wcpDelay = book.delay;
	} else {
		wcpAuto = false;
	}
	$('.flipbook').booklet({
        width:  bookWidth,
        height: bookHeight,
		pagePadding: 0,
		auto: wcpAuto,
        delay: wcpDelay,

    });
});		