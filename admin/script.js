jQuery(document).ready( function(){

    jQuery('.tab-content:nth-child(2)').addClass('firstelement');
    
    jQuery('#wcp-loader').hide();
    jQuery('#wcp-saved').hide();
    var sCounter = 1;
    var icons = {
        header: "dashicons dashicons-arrow-right-alt2",
        activeHeader: "dashicons dashicons-arrow-down-alt2"
    };    

    jQuery( "#accordion" ).accordion({
      collapsible: true,
      icons: icons,
      active: false
      // header: '.ui-accordion-header-icon'
    }); 

    jQuery(".thumbs-prev").sortable({
      placeholder: "ui-state-highlight"
    });

    var wcp_photo_book;
     
    jQuery('.upload_image_button').live('click', function( event ){
     
        event.preventDefault();
     
        var parent = jQuery(this).closest('.tab-content').find('.thumbs-prev');
        // Create the media frame.
        wcp_photo_book = wp.media.frames.wcp_photo_book = wp.media({
          title: 'Select Pages for Photo Book',
          button: {
            text: 'Add',
          },
          multiple: true  // Set to true to allow multiple files to be selected
        });
     
        // When an image is selected, run a callback.
        wcp_photo_book.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            var selection = wcp_photo_book.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                parent.append('<div><img src="'+attachment.url+'"><span class="dashicons dashicons-dismiss"></span></div>');
                jQuery("#accordion").accordion('refresh');

            });  
        });
     
        // Finally, open the modal
        wcp_photo_book.open();
    });

    jQuery('.thumbs-prev').on('click', '.dashicons-dismiss', function() {
        jQuery(this).parent('div').remove();
    });

    jQuery('#photo-book').on('click', '.save-pages', function(event) {
        event.preventDefault();
        jQuery('#wcp-saved').hide();
        jQuery('#wcp-loader').show();

        var allbooks = [];

        jQuery('#accordion > div').each(function(index) {
            var book = {};
            book.shortcode = jQuery(this).find('.fullshortcode').text();
            book.width = jQuery(this).find('.bookwidth').val();
            book.height = jQuery(this).find('.bookheight').val();
            book.speedofturn = jQuery(this).find('.speedofturn').val();
            book.startingpage = jQuery(this).find('.startingpage').val();
            book.readingdirection = jQuery(this).find('.readingdirection').val();
            book.pagepadding = jQuery(this).find('.pagepadding').val();

            if (jQuery(this).find('.pagenumbers').is(":checked")){ book.pagenumbers = true; } else { book.pagenumbers = false; }
            if (jQuery(this).find('.closedbook').is(":checked")){ book.closedbook = true; } else { book.closedbook = false; }
            if (jQuery(this).find('.autoplay').is(":checked")){ book.autoplay = true; } else { book.autoplay = false; }
            if (jQuery(this).find('.zoomonhover').is(":checked")){ book.zoomonhover = true; } else { book.zoomonhover = false; }
                
            book.booktitle = jQuery(this).find('.booktitle').val();
            book.autodelay = jQuery(this).find('.bookautoplaydelay').val();

            if (jQuery(this).find('.manualcontrol').is(":checked")){ book.manualcontrol = true; } else { book.manualcontrol = false; }
            if (jQuery(this).find('.keyboardcontrols').is(":checked")){ book.keyboardcontrols = true; } else { book.keyboardcontrols = false; }
            if (jQuery(this).find('.booktabs').is(":checked")){ book.booktabs = true; } else { book.booktabs = false; }
            if (jQuery(this).find('.bookarrows').is(":checked")){ book.bookarrows = true; } else { book.bookarrows = false; }

            book.counter = jQuery(this).find('.shortcode').text();
            
            book.pages = [];

            jQuery(this).find('.thumbs-prev div').each(function(index) {
                book.pages[index] = jQuery(this).find('img').attr('src');
            });

            allbooks.push(book);

        });

        // console.log(allbooks);
        var data = {
            action: 'wcp_save_photo_book_pages',
            books: allbooks,
        }

        jQuery.post(wcpAjax.url, data, function(resp) {
            jQuery('#wcp-loader').hide();
            jQuery('#wcp-saved').show();
        });

    });
  

    jQuery('#accordion .btnadd').click(function(event) {
        event.preventDefault();
        sCounter++;
        jQuery('.tab-content').find('.thumbs-prev').sortable('destroy');
        jQuery( "#accordion" ).append('<h3>Photo Book</h3>');
        jQuery(this).closest('.ui-accordion-content').clone(true).removeClass('firstelement').appendTo('#accordion').find('.shortcode').text(sCounter);
        jQuery("#accordion").accordion('refresh');
        jQuery('.tab-content').find('.thumbs-prev').sortable();
        // var header = jQuery(this).closest('.tab-head');
    });
    jQuery('#accordion .btndelete').click(function(event) {
        event.preventDefault();
        if (jQuery(this).closest('.ui-accordion-content').hasClass('firstelement')) {
            alert('You can not delete it as it is first element!');
        } else {
            var head = jQuery(this).closest('.ui-accordion-content').prev();
            var body = jQuery(this).closest('.ui-accordion-content');
            head.remove();
            body.remove();
            jQuery("#accordion").accordion('refresh');
        }
    });

    jQuery('button.viewshortcode').click(function(event) {
        event.preventDefault();
        prompt("Copy and use this Shortcode", '[photo-book-'+jQuery(this).closest('p.wcp-shortc').find('span.shortcode').text()+']');
    });

});    