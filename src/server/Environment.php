<?php


class Environment {


// memory /////////////////////////////////////////////////////////////////////

	private $instances;


// instantiation //////////////////////////////////////////////////////////////

	public function __construct() {
		$this->instances = Array();
	}


// interaction ////////////////////////////////////////////////////////////////

	public function __get( $key ){
		$instances = $this->instances;
		switch( $key ){

			case 'imageRepository':
				if( empty($instances['imageRepository']) ){
					require_once( "server/ImageRepository.php" );
					$instances['imageRepository'] = new ImageRepository( $this->fileHelper , $this->imagePath );
				}
				return $instances['imageRepository'];

			case 'videoRepository':
				if( empty($instances['videoRepository']) ){
					require_once( "server/VideoRepository.php" );
					$instances['videoRepository'] = new VideoRepository( $this->fileHelper , $this->videoPath );
				}
				return $instances['videoRepository'];

			case 'fileHelper':
				if( empty($instances['fileHelper']) ){
					require_once( "server/FileHelper.php" );
					$instances['fileHelper'] = new FileHelper();
				}
				return $instances['fileHelper'];

			case 'restRequestHandler':
				if( empty($instances['restRequestHandler']) ){
					require_once( "server/RestRequestHandler.php" );
					$instances['restRequestHandler'] = new RestRequestHandler( $this->imageRepository , $this->videoRepository );
				}
				return $instances['restRequestHandler'];

			case 'imagePath':
				return "images/";

			case 'videoPath':
				return "videos/";

			default:
				throw new Exception( "No such implementation configured: '$key'." );
		}
	}

}

