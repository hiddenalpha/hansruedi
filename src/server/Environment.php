<?php


class Environment {


	private $instances;


	public function __construct() {
		$this->instances = Array();
	}


	public function __get( $key ){
		switch( $key ){

			case 'fileHelper':
				if( empty($this->instances['fileHelper']) ){
					require_once( "server/FileHelper.php" );
					$this->instances['fileHelper'] = new FileHelper();
				}
				return $this->instances['fileHelper'];

			case 'restRequestHandler':
				if( empty($this->instances['restRequestHandler']) ){
					require_once( "server/RestRequestHandler.php" );
					$this->instances['restRequestHandler'] = new RestRequestHandler( $this->fileHelper , $this->imagePath , $this->videoPath );
				}
				return $this->instances['restRequestHandler'];

			case 'imagePath':
				return "images/";

			case 'videoPath':
				return "videos/";

			default:
				throw new Exception( "No such implementation configured: '$key'." );
		}
	}

}

