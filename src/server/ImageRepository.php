<?php


class ImageRepository {


// memory /////////////////////////////////////////////////////////////////////

	private $fileHelper;
	private $imagePath;
	private $imageMeta;


// initialization /////////////////////////////////////////////////////////////

	public function __construct( FileHelper $fileHelper , $imagePath ){
		$this->fileHelper = $fileHelper;
		$this->imagePath = $imagePath;
		$this->imageMeta = new stdClass();
	}


// interaction ////////////////////////////////////////////////////////////////

	public function getImages(){
		$paths = scandir( $this->imagePath );
		$images = Array();
		foreach( $paths AS $path ){
			$id = $file = basename( $path );
			if( $file=='.' || $file=='..' || $path == '.DS_Store'){
				// Ignore current and parent directory.
			}else if( preg_match("/\.meta\.json$/i",$id) ){
				// Ignore meta files.
			}else if( preg_match("/\.thumb\.[^.]+$/",$id) ){
				// Ignore thumbnail files.
			}else{
				$image = $this->createImage( $id );
				array_push( $images , $image );
			}
		}
		return $images;
	}

	public function getImage( $id ){
		return $this->createImage( $id , true );
	}

	private function createImage( $id ){
		$file = $this->imagePath . $id;
		if( file_exists($file) ){
			$meta = $this->getImageMeta( $id );
			$image = new stdClass();
			$image->id = $id;
			$fileExtension = $this->fileHelper->getExtension( $file );
			$image->mime = $this->fileHelper->getMimeOfExtension( $fileExtension );
			$image->description = $meta ? $meta->description : null;
			$image->writeTo = function( $oStream )use( $file ){
				$fd = fopen( $file , "r" );
				stream_copy_to_stream( $fd , $oStream );
				fclose( $fd );
			};
			return $image;
		}else{
			return null;
		}
	}

	/**
	 * @return
	 * 		the filename of the meta file corresponding to the specified video id.
	 */
	private function getMetaFileNameByImageId( $id ){
		return $this->getPureFileNameByImageId( $id ) .".meta.json";
	}

	/**
	 * @return
	 * 		The filename without the extension.
	 */
	private function getPureFileNameByImageId( $id ){
		preg_match( "/^(.*)\.[^.]+$/" , $id , $matches );
		return $matches[1];
	}

	/**
	 * @return
	 * 		The image meta to the provided image id or null if there is no meta
	 * 		available for this id.
	 */
	private function getImageMeta( $id ){
		if( !isset($this->imageMeta->$id) ){
			// Initialize metadata from file.
			$fileName = $this->getMetaFileNameByImageId( $id );
			$filePath = $this->imagePath . $fileName;
			if( file_exists($filePath) ){
				$this->imageMeta->$id = json_decode( file_get_contents($filePath) );
			}else{
				$this->imageMeta->$id = null;
			}
		}
		return $this->imageMeta->$id;
	}

}
