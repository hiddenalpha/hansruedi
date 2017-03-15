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
		$this->initImageMeta();
	}

	private function initImageMeta(){
		if( $this->imageMeta ) throw new Exception( "Image meta is already initialized." );

		$this->imageMeta = new stdClass();

		$id = "01.jpeg";
		$this->imageMeta->$id = new stdClass();
		$this->imageMeta->$id->id = $id;
		$this->imageMeta->$id->description = "Ein Bild";

		$id = "02.jpeg";
		$this->imageMeta->$id = new stdClass();
		$this->imageMeta->$id->id = $id;
		$this->imageMeta->$id->description = "Ein anderes Bild";

		$id = "03.jpeg";
		$this->imageMeta->$id = new stdClass();
		$this->imageMeta->$id->id = $id;
		$this->imageMeta->$id->description = "Noch ein Bild";
	}


// interaction ////////////////////////////////////////////////////////////////

	public function getImages(){
		$paths = scandir( $this->imagePath );
		$images = Array();
		foreach( $paths AS $path ){
			$id = $file = basename( $path );
			if( $file=='.' || $file=='..' || $path == '.DS_Store') continue; // Ignore current and parent directory.
			$image = $this->createImage( $id );
			//unset( $image->mime );
			array_push( $images , $image );
		}
		return $images;
	}

	public function getImage( $id ){
		return $this->createImage( $id , true );
	}

	private function createImage( $id , $attachWriter=false ){
		$file = $this->imagePath . $id;
		if( file_exists($file) ){
			$meta = $this->getImageMeta( $id );
			$image = new stdClass();
			$image->id = $id;
			$image->mime = $meta->mime;
			$image->description = $meta->description;
			if( $attachWriter ){
				$image->writeTo = function( $oStream )use( $file ){
					$fd = fopen( $file , "r" );
					stream_copy_to_stream( $fd , $oStream );
					fclose( $fd );
				};
			}
			return $image;
		}else{
			return null;
		}
	}

	private function getImageMeta( $id ){
		if( !isset($this->imageMeta->$id) ){
			// No meta yet. Create a dynamic one
			$this->imageMeta->$id = new stdClass();
		}
		$meta = $this->imageMeta->$id; // Shorthand name for simple access.
		if( empty($meta->description) ) $meta->description = "";
		$meta->mime = $this->fileHelper->getMimeOfExtension( $this->fileHelper->getExtension($id) ); // Using id becuase it equals to filename.
		return $meta;
	}

}
