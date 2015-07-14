<?php
/**
* Plugin Main Class
*/
class WCP_Photo_Book
{
	
	function __construct()
	{
		add_action( 'plugins_loaded', array($this, 'wcp_load_plugin_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'photo_book_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_options_page_scripts' ) );
		add_action('wp_ajax_wcp_save_photo_book_pages', array($this, 'save_pages'));

		$allbooks = get_option('wcp_photo_book');
		if (isset($allbooks['books'])) {
			foreach ($allbooks['books'] as $key => $data) {
				$shortcode = $data['shortcode'];
				//extracting the real shortcode from []
				preg_match_all("/\[([^\]]*)\]/", $shortcode, $matches);
				$full_shortcode = $matches[1][0];
				add_shortcode( $full_shortcode, array( $this, 'render_all_shortcodes' ) );
			}
		}
	}

	function wcp_load_plugin_textdomain(){
		load_plugin_textdomain( 'photo-book-gallery', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
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
		add_menu_page( 'Photo Book Gallery', 'Photo Book', 'manage_options', 'photo_book', array($this, 'render_menu_page'), 'dashicons-format-image' );
	}

	function render_menu_page(){
		$allbooks = get_option('wcp_photo_book');
		?>
			<div class="wrap" id="photo-book">
				<h2><?php _e( 'Photo Book Gallery Settings', 'photo-book-gallery' ); ?> <a title="Need Help?" target="_blank" href="http://webcodingplace.com/photo-book-gallery-wordpress-plugin/"><span class="dashicons dashicons-editor-help"></span></a></h2>

				<div id="accordion">
				<?php if (isset($allbooks['books'])) { ?>
				
					<?php foreach ($allbooks['books'] as $key => $data) { ?>
			  		<h3 class="tab-head"><?php if (isset($data['booktitle']) && $data['booktitle'] != '' ) { echo $data['booktitle']; } else { _e( 'Photo Book' , 'photo-book-gallery' );} ?></h3>
			  		<div class="tab-content">
				  		<h3><?php _e( 'General Settings', 'photo-book-gallery' ); ?></h3>
						<table class="form-table">
							<tr>
								<td><?php _e( 'Book Width', 'photo-book-gallery' ); ?></td>
								<td><input class="bookwidth widefat" type="number" value="<?php echo $data['width']; ?>"></td>
								<td><?php _e( 'Book Height', 'photo-book-gallery' ); ?></td>
								<td><input class="bookheight widefat" type="number" value="<?php echo $data['height']; ?>"></td>
							</tr>
							<tr>
								<td><?php _e( 'Speed', 'photo-book-gallery' ); ?></td>
								<td><input class="speedofturn widefat" type="number" value="<?php echo $data['speedofturn']; ?>"></td>
								<td><?php _e( 'Starting Page', 'photo-book-gallery' ); ?></td>
								<td><input class="startingpage widefat" type="number" value="<?php echo $data['startingpage']; ?>"></td>
							</tr>
							<tr>
								<td><?php _e( 'Reading Direction', 'photo-book-gallery' ); ?></td>
								<td>
									<select class="readingdirection widefat">
										<option value="RTL" <?php selected( $data['readingdirection'], 'RTL' ); ?>><?php _e( 'Right to Left', 'photo-book-gallery' ); ?></option>
										<option value="LTR" <?php selected( $data['readingdirection'], 'LTR' ); ?>><?php _e( 'Left to Right', 'photo-book-gallery' ); ?></option>
									</select>
								</td>
								<td><?php _e( 'Page Padding', 'photo-book-gallery' ); ?></td>
								<td><input class="pagepadding widefat" type="number" value="<?php echo $data['pagepadding']; ?>"></td>
							</tr>
							<tr>
								<td><?php _e( 'Page Numbers', 'photo-book-gallery' ); ?></td>
								<td><label><input class="pagenumbers widefat" type="checkbox" <?php checked( $data['pagenumbers'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Closed Book', 'photo-book-gallery' ); ?></td>
								<td><label><input class="closedbook widefat" type="checkbox" <?php checked( $data['closedbook'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
							<tr>
								<td><?php _e( 'Book Title (for your reference)', 'photo-book-gallery' ); ?></td>
								<td><input class="booktitle widefat" type="text" value="<?php if (isset($data['booktitle'])) { echo $data['booktitle']; } ?>"></td>
								<td><?php _e( 'Zoom on Hover', 'photo-book-gallery' ); ?></td>
								<td><label><input class="zoomonhover widefat" type="checkbox" <?php  if (isset($data['zoomonhover'])) { checked( $data['zoomonhover'], 'true' ); } ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
						</table>
			  			<h3><?php _e( 'Controls', 'photo-book-gallery' ); ?></h3>
						<table class="form-table">
							<tr>
								<td><?php _e( 'AutoPlay', 'photo-book-gallery' ); ?></td>
								<td><label><input class="autoplay widefat" type="checkbox" <?php checked( $data['autoplay'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'AutoPlay delay for each page (in ms)', 'photo-book-gallery' ); ?></td>
								<td><input class="bookautoplaydelay widefat" type="number" value="<?php echo $data['autodelay']; ?>"></td>
							</tr>
							<tr>
								<td><?php _e( 'Turn Page by clicking Image', 'photo-book-gallery' ); ?></td>
								<td><label><input class="manualcontrol widefat" type="checkbox" <?php checked( $data['manualcontrol'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Keyboard Controls', 'photo-book-gallery' ); ?></td>
								<td><label><input class="keyboardcontrols widefat" type="checkbox" <?php checked( $data['keyboardcontrols'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
							<tr>
								<td><?php _e( 'Navigation Tabs', 'photo-book-gallery' ); ?></td>
								<td><label><input class="booktabs widefat" type="checkbox" <?php checked( $data['booktabs'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Arrows', 'photo-book-gallery' ); ?></td>
								<td><label><input class="bookarrows widefat" type="checkbox" <?php checked( $data['bookarrows'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
							</tr>
						</table>
						<hr style="margin-bottom: 15px;">
						<div class="thumbs-prev">
							<?php if ($data['pages'] != '') {
								foreach ($data['pages'] as $key => $value) {
									echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span></div>';
								}
							} ?>
						</div>
						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span><?php _e( 'Delete', 'photo-book-gallery' ); ?></button>
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span><?php _e( 'Add New Book', 'photo-book-gallery' ); ?></button>&nbsp;
						<button class="button-secondary upload_image_button"><span class="dashicons dashicons-images-alt2"></span><?php _e( 'Upload Images', 'photo-book-gallery' ); ?></button>&nbsp;
						<p class="wcp-shortc"><?php _e( 'Shortcode', 'photo-book-gallery' ); ?>: <b><span class="fullshortcode">[photo-book-<span contenteditable="true" class="shortcode"><?php echo $data['counter']; ?></span>]</span></b></p>
					</div>
					<?php } ?>
				<?php } else { ?>
					<h3 class="tab-head"><?php _e( 'Photo Book', 'photo-book-gallery' ); ?></h3>
			  		<div class="tab-content">
			  			<h3><?php _e( 'General Settings', 'photo-book-gallery' ); ?></h3>
						<table class="form-table">
							<tr>
								<td><?php _e( 'Book Width', 'photo-book-gallery' ); ?></td>
								<td><input class="bookwidth widefat" type="number" value="450"></td>
								<td><?php _e( 'Book Height', 'photo-book-gallery' ); ?></td>
								<td><input class="bookheight widefat" type="number" value="450"></td>
							</tr>
							<tr>
								<td><?php _e( 'Speed', 'photo-book-gallery' ); ?></td>
								<td><input class="speedofturn widefat" type="number" value="1000"></td>
								<td><?php _e( 'Starting Page', 'photo-book-gallery' ); ?></td>
								<td><input class="startingpage widefat" type="number" value=""></td>
							</tr>
							<tr>
								<td><?php _e( 'Reading Direction', 'photo-book-gallery' ); ?></td>
								<td>
									<select class="readingdirection widefat">
										<option value="RTL" <?php selected( $data['readingdirection'], 'RTL' ); ?>><?php _e( 'Right to Left', 'photo-book-gallery' ); ?></option>
										<option value="LTR" <?php selected( $data['readingdirection'], 'LTR' ); ?>><?php _e( 'Left to Right', 'photo-book-gallery' ); ?></option>
									</select>
								</td>
								<td><?php _e( 'Page Padding', 'photo-book-gallery' ); ?></td>
								<td><input class="pagepadding widefat" type="number" value="0"></td>
							</tr>
							<tr>
								<td><?php _e( 'Page Numbers', 'photo-book-gallery' ); ?></td>
								<td><label><input class="pagenumbers widefat" type="checkbox" <?php checked( $data['pagenumbers'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Closed Book', 'photo-book-gallery' ); ?></td>
								<td><label><input class="closedbook widefat" type="checkbox" <?php checked( $data['closedbook'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
						</table>
			  			<h3><?php _e( 'Controls', 'photo-book-gallery' ); ?></h3>
						<table class="form-table">
							<tr>
								<td><?php _e( 'AutoPlay', 'photo-book-gallery' ); ?></td>
								<td><label><input class="autoplay widefat" type="checkbox" <?php checked( $data['autoplay'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'AutoPlay delay for each page (in ms)', 'photo-book-gallery' ); ?></td>
								<td><input class="bookautoplaydelay widefat" type="number" value="1000"></td>
							</tr>
							<tr>
								<td><?php _e( 'Turn Page by clicking anywhere', 'photo-book-gallery' ); ?></td>
								<td><label><input class="manualcontrol widefat" type="checkbox" <?php checked( $data['manualcontrol'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Keyboard Controls', 'photo-book-gallery' ); ?></td>
								<td><label><input class="keyboardcontrols widefat" type="checkbox" <?php checked( $data['keyboardcontrols'], 'true' ); ?>><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
							<tr>
								<td><?php _e( 'Navigation Tabs', 'photo-book-gallery' ); ?></td>
								<td><label><input class="booktabs widefat" type="checkbox" <?php checked( $data['booktabs'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
								<td><?php _e( 'Arrows', 'photo-book-gallery' ); ?></td>
								<td><label><input class="bookarrows widefat" type="checkbox" <?php checked( $data['bookarrows'], 'true' ); ?>><?php _e( 'Show', 'photo-book-gallery' ); ?></label></td>
							</tr>
							<tr>
								<td><?php _e( 'Book Title (for your reference)', 'photo-book-gallery' ); ?></td>
								<td><input class="booktitle widefat" type="text" value=""></td>
								<td><?php _e( 'Zoom on Hover', 'photo-book-gallery' ); ?></td>
								<td><label><input class="zoomonhover widefat" type="checkbox"><?php _e( 'Enable', 'photo-book-gallery' ); ?></label></td>
							</tr>
						</table>
						<hr style="margin-bottom: 10px;">
						<div class="thumbs-prev">
							<?php if ($allbooks['pages'] != '') {
								foreach ($allbooks['pages'] as $key => $value) {
									echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span></div>';
								}
							} ?>
						</div>
						<div class="clearfix"></div>
						<hr style="margin-bottom: 10px;">
						
						<button class="button btndelete"><span class="dashicons dashicons-dismiss" title="Delete"></span> <?php _e( 'Delete', 'photo-book-gallery' ); ?></button>
						<button class="button btnadd"><span title="Add New" class="dashicons dashicons-plus-alt"></span> <?php _e( 'Add New Book', 'photo-book-gallery' ); ?></button>&nbsp;
						<button class="button-secondary upload_image_button"><?php _e( 'Upload Images', 'photo-book-gallery' ); ?></button>&nbsp;
						Shortcode: <b><span class="fullshortcode">[photo-book-<span contenteditable="true" class="shortcode">1</span>]</span></b>
					</div>
				<?php } ?>
				</div>

				<hr style="clear: both;">
				<button class="button-primary save-pages"><?php _e( 'Save Changes', 'photo-book-gallery' ); ?></button>
				<span id="wcp-loader"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/ajax-loader.gif"></span>
				<span id="wcp-saved"><strong><?php _e( 'Changes Saved', 'photo-book-gallery' ); ?>!</strong></span>				
			</div>
		<?php
	}

	function render_all_shortcodes($atts, $content, $the_shortcode){

		$allbooks = get_option('wcp_photo_book');

		if (isset($allbooks['books'])) {
			foreach ($allbooks['books'] as $key => $data) {
				$shortcode = $data['shortcode'];
				//extracting the real shortcode from []
				preg_match_all("/\[([^\]]*)\]/", $shortcode, $matches);
				$full_shortcode = $matches[1][0];
				if ($the_shortcode == $full_shortcode) {

					wp_register_script( 'wcp-custom-script', plugins_url( 'js/script.js' , __FILE__ ), array('jquery', 'jquery-ui-core') );

					wp_localize_script( 'wcp-custom-script', 'book', array(
										'width' => $data['width'],
										'height' => $data['height'],
										'speedofturn' => $data['speedofturn'],
										'startingpage' => $data['startingpage'],
										'readingdirection' => $data['readingdirection'],
										'pagepadding' => $data['pagepadding'],
										'pagenumbers' => $data['pagenumbers'],
										'closedbook' => $data['closedbook'],
										'autoplay' => $data['autoplay'],
										'delay' => $data['autodelay'],
										'manualcontrol' => $data['manualcontrol'],
										'keyboardcontrols' => $data['keyboardcontrols'],
										'booktabs' => $data['booktabs'],
										'bookarrows' => $data['bookarrows'],
										'zoomonhover' => $data['zoomonhover'],
									));

					wp_enqueue_style( 'book-css', plugins_url( 'css/jquery.booklet.latest.css' , __FILE__ ));
					wp_enqueue_script( 'easing-js', plugins_url( 'js/jquery.easing.1.3.js' , __FILE__ ), array('jquery') );
					wp_enqueue_script( 'zoom-js', plugins_url( 'js/jquery.zoom.min.js' , __FILE__ ), array('jquery') );
					if ($data['zoomonhover'] == true) {
						wp_enqueue_script( 'book-js', plugins_url( 'js/jquery.booklet.latest.min.js' , __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-draggable') );
					}
					wp_enqueue_script( 'wcp-custom-script');

					$bookContents = '<div class="flipbook">';
						if ($data['pages'] != '') {
							foreach ($data['pages'] as $value) {
								$bookContents .= '<div><img src="'.$value.'"></div>';
							}
						}
					$bookContents .= '</div>';

					return $bookContents;
				}
			}
		}		
	}
}

?>