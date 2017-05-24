<?php


class RestRequestHandler {


// memory /////////////////////////////////////////////////////////////////////

	private $imageRepository;
	private $videoRepository;


// instantiation //////////////////////////////////////////////////////////////

	public function __construct( ImageRepository $imageRepository , VideoRepository $videoRepository ){
		$this->imageRepository = $imageRepository;
		$this->videoRepository = $videoRepository;
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


		// Images
		if( $method=='GET' && preg_match("/^\/images\/?$/",$path) ){
			$this->printImageList();
		}else if( $method=='GET' && preg_match("/^\/images\/([^\/]+)\/?$/",$path,$matches) ){
			$id = basename( $matches[0] );
			$this->sendImage( $id );
		}
		// Videos
		else if( $method=='GET' && preg_match("/^\/videos\/?$/",$path,$matches) ){
			$this->printVideoList();
		}else if( $method=='GET' && preg_match("/^\/videos\/([^\/]+)\/?$/",$path,$matches) ){
			$id = basename( $matches[0] );
			$this->sendVideo( $id );
		}
		// Default
		else{
			http_response_code( 400 );
			header( "Content-Type: text/plain" );
			echo "Bad request.\n\nThe server didn't understand what you want.\n";
		}
	}

	private function printImageList(){
		$images = $this->imageRepository->getImages();
		header( "Content-Type: application/json" );
		echo json_encode( $images );
	}

	private function printVideoList(){
		$videos = $this->videoRepository->getVideos();
		header( "Content-Type: application/json" );
		echo json_encode( $videos );
	}

	private function sendImage( $id ){
		$image = $this->imageRepository->getImage( $id );
		if( $image ){
			header( "Content-Type: {$image->mime}" );
			$oStream = fopen( "php://output" , "w" );
			$writeTo = $image->writeTo; // Copy lambda out of object.
			$writeTo( $oStream ); // call lambda
			fclose( $oStream );
		}else{
			http_response_code( 404 );
			echo "Image Not Found";
		}
	}

	private function sendVideo( $id ) {
		$video = $this->videoRepository->getVideo( $id );
		if( $video ){
			header( "Content-Type: {$video->mime}" );
			$oStream = fopen( "php://output" , "w" );
			$writeTo = $video->writeTo; // Copy lambda out.
			$writeTo( $oStream ); // call lambda
			fclose( $oStream );
		}else{
			http_response_code( 404 );
			echo "Video Not Found";
		}
	}

}

