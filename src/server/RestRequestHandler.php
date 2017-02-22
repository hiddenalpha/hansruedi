<?php


class RestRequestHandler {

	private $imageHelper;
	private $fileHelper;
	private $imagePath;
	

	public function __construct( ImageHelper $imageHelper , FileHelper $fileHelper , $imagePath ){
		$this->imageHelper = $imageHelper;
		$this->fileHelper = $fileHelper;
		$this->imagePath = $imagePath;
	}


	public function handleRequest(){
		$httpHost = $_SERVER['HTTP_HOST'];
		$requestUri = $_SERVER['REQUEST_URI'];
		$path = $_SERVER['PATH_INFO'];
		$method = $_SERVER['REQUEST_METHOD'];

		// Defaults
		http_response_code( 200 );


		if( $method=='GET' && preg_match("/^\/images\/?$/",$path) ){
			$this->printImageList();
		}else if( $method=='GET' && preg_match("/^\/images\/([^\/]+)\/?$/",$path,$matches) ){
			$id = basename( $matches[0] );
			$this->sendImage( $id );
		}else{
			http_response_code( 400 );
			header( "Content-Type: text/plain" );
			echo "Bad request.\n\nThe server didn't understand what you want.\n";
		}
	}

	private function printImageList(){
		$paths = scandir( $this->imagePath );
		header( "Content-Type: application/json" );
		echo "[";
		$isFirst = true;
		foreach( $paths AS $path ){
			$file = basename( $path );
			if( $file=='.' || $file=='..' ) continue; // Ignore current and parent directory.
			if( !$isFirst ) echo",";
			echo '{ "id":"'.$file.'" }';
			$isFirst = false;
		}
		echo "]";
	}

	private function sendImage( $id ){
		$file = $this->imagePath . $id;
		if( file_exists($file) ){
			$ext = $this->fileHelper->getExtension( $file );
			$mime = $this->imageHelper->getMimeOfExtension( $ext );
			header( "Content-Type: $mime" );
			readfile( $file );
		}else{
			http_response_code( 404 );
			echo "Image not found";
		}
	}

}

