jQuery(document).ready(function($) {

	var width = "100%";
	var height = "450";
	var autoplay = false;
	var bookarrows = false;
	var booktabs = false;
	var closedbook = false;
	var delay = "2000";
	var keyboardcontrols = true;
	var manualcontrol = false;
	var pagenumbers = false;
	var pagepadding = 0;
	var readingdirection = "LTR";
	var speedofturn = 1000;
	var startingpage = 1;
	var manual =  true;

	if (book.width != '') { width = book.width * 2; }
	if (book.height != '') { height = book.height; }
	if (book.autoplay == 'true') { autoplay = true; }
	if (book.bookarrows == 'true') { bookarrows = true; }
	if (book.booktabs == 'true') { booktabs = true; }
	if (book.closedbook == 'true') { closedbook = true; }
	if (book.delay != '') { delay = book.delay; }
	if (book.keyboardcontrols == 'false') { keyboardcontrols = false; }
	if (book.manualcontrol == 'true') { manualcontrol = true; manual = false; }
	if (book.pagenumbers == 'true') { pagenumbers = true; }
	if (book.pagepadding != '') { pagepadding = book.pagepadding; }
	if (book.readingdirection != '') { readingdirection = book.readingdirection; }
	if (book.speedofturn != '') { speedofturn = book.speedofturn; }
	if (book.startingpage != '') { startingpage = book.startingpage; }


	$('.flipbook').booklet({
        width:  width,
        height: height,
		auto: autoplay,
		arrows: bookarrows,
		tabs: booktabs,
        closed: closedbook,
        autoCenter: closedbook,
        delay: delay,
        keyboard: keyboardcontrols,
        overlays: manualcontrol,
        manual: manual,
        pageNumbers: pagenumbers,
		pagePadding: pagepadding,
		direction: readingdirection,
		speed: speedofturn,
		startingPage: startingpage,
    });
});		