<?php
/**
* Plugin Main Class
*/
class WCP_Photo_Book
{
	
	function __construct()
	{
		add_action( 'wp_enqueue_scripts', array($this, 'load_plugin_script'));
		add_shortcode( 'photo-book', array($this, 'render_photo_book') );
		add_action( 'admin_menu', array( $this, 'photo_book_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_options_page_scripts' ) );
		add_action('wp_ajax_wcp_save_photo_book_pages', array($this, 'save_pages'));
	}

	function load_plugin_script(){
		$saved_pages = get_option('wcp_photo_book');
		wp_register_script( 'wcp-custom-script', plugins_url( 'js/script.js' , __FILE__ ), array('jquery', 'jquery-ui-core') );
		wp_localize_script( 'wcp-custom-script', 'book', array( 'width' => $saved_pages['width'], 'height' => $saved_pages['height']));
	}

	function admin_options_page_scripts($slug){
		if ($slug == 'toplevel_page_photo_book') {
			wp_enqueue_media();
			wp_enqueue_script( 'photo-book-admin-js', plugins_url( 'admin/script.js' , __FILE__ ), array('jquery', 'jquery-ui-sortable') );
			wp_enqueue_style( 'photo-book-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
			wp_localize_script( 'photo-book-admin-js', 'wcpAjax', array( 'url' => admin_url( 'admin-ajax.php' )));
		}
	}

	function save_pages(){
		if (isset($_REQUEST['pages'])) {
			update_option( 'wcp_photo_book', $_REQUEST );
		}

		die(0);
	}

	function render_photo_book(){
		$saved_pages = get_option('wcp_photo_book');
		wp_enqueue_style( 'book-css', plugins_url( 'css/jquery.booklet.latest.css' , __FILE__ ));
		wp_enqueue_script( 'easing-js', plugins_url( 'js/jquery.easing.1.3.js' , __FILE__ ), array('jquery') );
		wp_enqueue_script( 'book-js', plugins_url( 'js/jquery.booklet.latest.min.js' , __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-draggable') );
		wp_enqueue_script( 'wcp-custom-script');	
		?>
		<div class="flipbook">
			<?php if ($saved_pages['pages'] != '') {
				foreach ($saved_pages['pages'] as $key => $value) {
					echo '<div><img src="'.$value.'"></div>';
				}
			} ?>
		</div>
		<?php
	}

	function photo_book_admin_options(){
		add_menu_page( 'Photo Book', 'Photo Book', 'manage_options', 'photo_book', array($this, 'render_menu_page'), 'dashicons-format-image' );
	}

	function render_menu_page(){
		$saved_pages = get_option('wcp_photo_book');
		?>
			<div class="wrap" id="photo-book">
				<div>
					<h2>Photo Book Pages</h2>
					<p class="description">Use this shortcode to show Photo Book in site <b>[photo-book]</b>. All images should be of the same size, default size is 500x500.</p>
					<label>Book Width: <input id="bookwidth" type="text" value="<?php echo $saved_pages['width']; ?>"></label>
					<label>Book Height: <input id="bookheight" type="text" value="<?php echo $saved_pages['height']; ?>"></label>&nbsp;
					<button class="button-secondary upload_image_button">Upload Pages</button>
					<hr>
					<div class="thumbs-prev">
					<?php if ($saved_pages['pages'] != '') {
						foreach ($saved_pages['pages'] as $key => $value) {
							echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span></div>';
						}
					} ?>
					</div>
					<hr style="clear: both;">
					<button class="button-primary save-pages">Save Photo Book Pages</button>
					<span id="wcp-loader"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>pages/ajax-loader.gif"></span>
					<span id="wcp-saved"><strong>Changes Saved!</strong></span>
				</div>
			</div>
		<?php
	}
}

?>