<?php


class FileHelper {

	/**
	 * @return
	 * 		The file extension of the file given in the path or empty string if there is no extension.
	 */
	public function getExtension( $path ) {
		$pathParts = explode( '/' , $path );
		$nameParts = explode( '.' , $pathParts );
		if( count($nameParts) > 1 ){
			return $nameParts[ count($nameParts-1) ];
		}else{
			return "";
		}
	}

}

