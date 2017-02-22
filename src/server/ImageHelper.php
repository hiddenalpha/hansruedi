<?php


class ImageHelper {

	public function getMimeOfExtension( $ext ){
		// $ext = $ext.toUpperCase();
		switch( $ext ){
			case 'JPEG':
			case 'JPG':
				return 'image/jpeg';
			case 'PNG':
				return 'image/png';
			case 'GIF':
				return 'image/gif';
			default:
				error_log( "Failed to map '$ext' to a mime type." );
		}
	}

}

