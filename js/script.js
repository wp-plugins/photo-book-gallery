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
	$('.flipbook').booklet({
        width:  bookWidth,
        height: bookHeight,
		pagePadding: 0
    });
});		