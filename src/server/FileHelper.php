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
		return $nameParts[ $lastIndex ];
	}

	/**
	 * @param ext
	 * 		The file extension to map (without the dot).
	 * @return
	 * 		The MIME-Type string matching the passed file extension.
	 */
	public function getMimeOfExtension( $ext ){
		$ext = strtoupper( $ext );
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

