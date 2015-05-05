jQuery(document).ready( function(){
    
    jQuery('#wcp-loader').hide();
    jQuery('#wcp-saved').hide();

    jQuery(".thumbs-prev").sortable({
      placeholder: "ui-state-highlight"
    });

    var wcp_photo_book;
     
    jQuery('.upload_image_button').live('click', function( event ){
     
        event.preventDefault();
     
     
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
                jQuery('.thumbs-prev').append('<div><img src="'+attachment.url+'"><span class="dashicons dashicons-dismiss"></span></div>');

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
        var pages = [];
        jQuery('.thumbs-prev div').each(function(index) {
            // console.log(index + '  '+ jQuery(this).find('img').attr('src'));
            pages[index] = jQuery(this).find('img').attr('src');

        });
        var data = {
            action: 'wcp_save_photo_book_pages',
            pages: pages,
            width: jQuery('#bookwidth').val(),
            height: jQuery('#bookheight').val(),
        }

        jQuery.post(wcpAjax.url, data, function(resp) {
            jQuery('#wcp-loader').hide();
            jQuery('#wcp-saved').show();
            if (pages[0] == null) {
                location.reload();
            }
        });

    });

});    