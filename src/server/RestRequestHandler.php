<?php


class RestRequestHandler {


// memory /////////////////////////////////////////////////////////////////////

	private $fileHelper;
	private $imagePath;
	private $videoPath;


// instantiation //////////////////////////////////////////////////////////////

	public function __construct( FileHelper $fileHelper , $imagePath , $videoPath ){
		$this->fileHelper = $fileHelper;
		$this->imagePath = $imagePath;
		$this->videoPath = $videoPath;
	}


// interaction ////////////////////////////////////////////////////////////////

	/**
	 * Handles the request from the client and sends the response directly to
	 * the client. Make sure that There is no output neither before nor after
	 * the call to this method. Because else this one here cannot manipulate
	 * the HTTP response header properly anymore. And by sending after it you
	 * may make the output invalid.
	 */
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
		}else if( $method=='GET' && preg_match("/^\/videos\/?$/",$path,$matches) ){
			$this->printVideoList();
		}else if( $method=='GET' && preg_match("/^\/videos\/([^\/]+)\/?$/",$path,$matches) ){
			$id = basename( $matches[0] );
			$this->sendVideo( $id );
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

	private function printVideoList(){
		$paths = scandir( $this->videoPath );
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
			$mime = $this->fileHelper->getMimeOfExtension( $ext );
			header( "Content-Type: $mime" );
			readfile( $file );
		}else{
			http_response_code( 404 );
			echo "Image not found";
		}
	}

	private function sendVideo( $id ) {
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			$ext = $this->fileHelper->getExtension( $file );
			$mime = $this->fileHelper->getMimeOfExtension( $ext );
			header( "Content-Type: $mime" );
			readfile( $file );
		}else{
			http_response_code( 404 );
			echo "Video not found";
		}
	}

}

