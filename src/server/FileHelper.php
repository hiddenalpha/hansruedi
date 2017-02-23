<?php


class FileHelper {

	/**
	 * @return
	 * 		The file extension of the file given in the path or empty string if there is no extension.
	 */
	public function getExtension( $path ) {
		$fileName = basename( $path );
		$nameParts = explode( '.' , $fileName );
		$lastIndex = count($nameParts) - 1 ;
		if( $lastIndex == 0 ){
			// There is no extension. Return empty string
			return "";
		}else{
			// There is an extension. (there was at least a dot in the name)
			return $nameParts[ $lastIndex ];
		}
	}

	/**
	 * @param ext
	 * 		The file extension to map (without the dot).
	 * @return
	 * 		The MIME-Type string matching the passed file extension. If mime
	 * 		type cannot get evaluated 'application/octet-stream' will be
	 * 		returned as default.
	 */
	public function getMimeOfExtension( $ext ){
		$ext = strtoupper( $ext );
		switch( $ext ){
			case 'JPEG':
			case 'JPG':
				return 'image/jpeg';
			case 'PNG':
				return 'image/png';
			case 'FLV':
				return 'video/x-flv';
			case 'MP4':
				// Mp4 has multiple MIME types. Which should we use? [ video/mp4 , audio/mp4 , application/mp4 ]
				return 'video/mp4';
			case 'GIF':
				return 'image/gif';
			case 'WEBM':
				// Webm has two MIME types. Which should we return? [ video/webm , audio/webm ]
				return 'video/webm';
			default:
				error_log( "Failed to map '$ext' to a mime type. Fall back to 'application/octet-stream'" );
				return 'application/octet-stream';
		}
	}

}

