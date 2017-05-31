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
		// Video thumbnails
		else if( $method=='GET' && preg_match("/^\/videos\/([^\/]+)\/thumb$/",$path,$matches) ){
			$videoId = $matches[1];
			$this->sendVideoThumbnail( $videoId );
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
			// Get requested range.
			$this->evaluateRange( $start , $end );
			// Apply fallback if necessary.
			if( $end > $video->size || $end==-1 ) $end = $video->size;

			// Set common headers.
			header( "Content-Type: {$video->mime}" );
			header( "Accept-Ranges: bytes" );
			header( "Content-Length: ". ($end-$start) );
			if( $start != 0 || $end != $video->size ){
				// Set range-request specific headers.
				http_response_code( 206 );
				header( "Content-Range: bytes $start-". ($end-1) ."/{$video->size}" );
			}

			// Stream the requested content.
			$oStream = fopen( "php://output" , "w" );
			$video->writeTo( $oStream , $start , $end );
			fclose( $oStream );
		}else{
			http_response_code( 404 );
			echo "Video Not Found";
		}
	}

	private function sendVideoThumbnail( $videoId ){
		$thumbnail = $this->videoRepository->getThumbnail( $videoId );
		if( $thumbnail ){
			header( "Content-Type: {$thumbnail->mime}" );
			$oStream = fopen( "php://output" , "w" );
			$thumbnail->writeTo( $oStream );
			fclose( $oStream );
		}else{
			http_response_code( 404 );
			echo "Video Not Found";
		}

	}

	/**
	 * Evaluates the range requested by HTTP range request.
	 * @param start
	 * 		The Reference where the start of the range will be written to.
	 * @param end
	 * 		The Reference where the end of the range will be written to.
	 * 		Reserved value -1 means "until end of file"
	 */
	private function evaluateRange( &$start , &$end ){
		// Set default values.
		$start = 0;
		$end = -1;

		// Evaluate effective values.
		$headers = getallheaders();
		$rangeHeader = isset($headers['Range']) ? $headers['Range'] : null;
		// If this is a range request.
		if( $rangeHeader ){
			// Try to evaluate range.
			$range = explode( '=' , $rangeHeader );
			if( count($range) != 2 || $range[0]!="bytes" ){
				error_log( "Failed to parse range header. err_1496161433 Range: '$rangeHeader'" );
				return;
			}
			$range = $range[1]; // <- take part with numbers (eg: '0-5' )
			$range = explode( '-' , $range );
			if( count($range) != 2 ){
				error_log( "Failed to parse range header. err_1496161440. Range: '$rangeHeader'" );
				return;
			}
			$start = $range[0];
			$end = $range[1]=="" ? -1 : $range[1];
			//error_log( "DEBUG: range is from '$start' to '$end'." );
		}
	}

}

