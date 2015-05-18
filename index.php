<?php
/**
 * Plugin Name: Photo Book Gallery
 * Plugin URI: http://webcodingplace.com/photo-book-gallery
 * Description: An Amazing Book View of Images
 * Version: 3.0
 * Author: Rameez
 * Author URI: http://webcodingplace.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: photo-book
 */

/*

  Copyright (C) 2015  Rameez  rameez.iqbal@live.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
*/
require_once('plugin.class.php');

if( class_exists('WCP_Photo_Book')){
	
	$just_initialize = new WCP_Photo_Book;
}
?>