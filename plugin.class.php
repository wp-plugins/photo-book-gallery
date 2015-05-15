<?php
/**
* Plugin Main Class
*/
class WCP_Photo_Book
{
	
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'photo_book_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_options_page_scripts' ) );
		add_action('wp_ajax_wcp_save_photo_book_pages', array($this, 'save_pages'));

		$allbooks = get_option('wcp_photo_book');

		foreach ($allbooks['books'] as $key => $data) {
			$shortcode = $data['shortcode'];
			//extracting the real shortcode from []
			preg_match_all("/\[([^\]]*)\]/", $shortcode, $matches);
			$full_shortcode = $matches[1][0];
			add_shortcode( $full_shortcode, function() use ($data){

				wp_register_script( 'wcp-custom-script', plugins_url( 'js/script.js' , __FILE__ ), array('jquery', 'jquery-ui-core') );
				wp_localize_script( 'wcp-custom-script', 'book', array( 'width' => $data['width'], 'height' => $data['height']));
				wp_enqueue_style( 'book-css', plugins_url( 'css/jquery.booklet.latest.css' , __FILE__ ));
				wp_enqueue_script( 'easing-js', plugins_url( 'js/jquery.easing.1.3.js' , __FILE__ ), array('jquery') );
				wp_enqueue_script( 'book-js', plugins_url( 'js/jquery.booklet.latest.min.js' , __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-draggable') );
				wp_enqueue_script( 'wcp-custom-script');

				echo '<div class="flipbook">';
					if ($data['pages'] != '') {
						foreach ($data['pages'] as $value) {
							echo '<div><img src="'.$value.'"></div>';
						}
					}
				echo '</div>';
			} );
		}
	}

	function admin_options_page_scripts($slug){
		if ($slug == 'toplevel_page_photo_book') {
			wp_enqueue_media();
			wp_enqueue_script( 'photo-book-admin-js', plugins_url( 'admin/script.js' , __FILE__ ), array('jquery', 'jquery-ui-sortable', 'jquery-ui-accordion') );
			wp_enqueue_style( 'photo-book-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
			wp_localize_script( 'photo-book-admin-js', 'wcpAjax', array( 'url' => admin_url( 'admin-ajax.php' )));
		}
	}

	function save_pages(){
		if (isset($_REQUEST)) {
			update_option( 'wcp_photo_book', $_REQUEST );
		}

		die(0);
	}

	function photo_book_admin_options(){
		add_menu_page( 'Photo Book', 'Photo Book', 'manage_options', 'photo_book', array($this, 'render_menu_page'), 'dashicons-format-image' );
	}

	function render_menu_page(){
		$allbooks = get_option('wcp_photo_book');
		?>
			<div class="wrap" id="photo-book">
				<h2>Photo Book Gallery <a title="Need Help?" target="_blank" href="http://webcodingplace.com/photo-book-gallery/"><span class="dashicons dashicons-editor-help"></span></a></h2>

				<div id="accordion">
				<?php if (isset($allbooks['books'])) { ?>
				
					<?php foreach ($allbooks['books'] as $key => $data) { ?>
			  		<h3 class="tab-head">Photo Book</h3>
			  		<div class="tab-content">
						<div class="thumbs-prev">
							<?php if ($data['pages'] != '') {
								foreach ($data['pages'] as $key => $value) {
									echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span></div>';
								}
							} ?>
						</div>
						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						<label>Book Width: <input class="bookwidth" type="text" value="<?php echo $data['width']; ?>"></label>
						<label>Book Height: <input class="bookheight" type="text" value="<?php echo $data['height']; ?>"></label>&nbsp;
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span>Delete</button>
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span>Add New Book</button>&nbsp;
						<button class="button-secondary upload_image_button"><span class="dashicons dashicons-images-alt2"></span>Upload Images</button>&nbsp;
						Shortcode: <b><span class="fullshortcode">[photo-book-<span contenteditable="true" class="shortcode"><?php echo $data['counter']; ?></span>]</span></b>
					</div>
					<?php } ?>
				<?php } else { ?>
					<h3 class="tab-head">Photo Book</h3>
			  		<div class="tab-content">
						<div class="thumbs-prev">
							<?php if ($allbooks['pages'] != '') {
								foreach ($allbooks['pages'] as $key => $value) {
									echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span></div>';
								}
							} ?>
						</div>
						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						<label>Book Width: <input class="bookwidth" type="text" value=""></label>
						<label>Book Height: <input class="bookheight" type="text" value=""></label>&nbsp;
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span></button>
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span></button>&nbsp;
						<button class="button-secondary upload_image_button">Upload Images</button>&nbsp;
						Shortcode: <b><span class="fullshortcode">[photo-book-<span contenteditable="true" class="shortcode">1</span>]</span></b>
					</div>
				<?php } ?>
				</div>

				<hr style="clear: both;">
				<button class="button-primary save-pages">Save Changes</button>
				<span id="wcp-loader"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>pages/ajax-loader.gif"></span>
				<span id="wcp-saved"><strong>Changes Saved!</strong></span>				
			</div>
		<?php
	}
}

?>