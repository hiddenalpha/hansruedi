<?php


class VideoRepository {


// memory /////////////////////////////////////////////////////////////////////

	private $fileHelper;
	private $videoPath;
	private $videoMeta;


// initialization /////////////////////////////////////////////////////////////

	public function __construct( FileHelper $fileHelper , $videoPath ){
		$this->fileHelper = $fileHelper;
		$this->videoPath = $videoPath;
		$this->initVideoMeta();
	}

	private function initVideoMeta(){
		if( $this->videoMeta ) throw new Exception( "'videoMeta' is alredy initialized" );
		$this->videoMeta = new stdClass();

		$id = "empty-01.flv";
		$this->videoMeta->$id = new stdClass();
		$this->videoMeta->$id->description = "Ein Video";

		$id = "empty-02.webm";
		$this->videoMeta->$id = new stdClass();
		$this->videoMeta->$id->description = "Ein anderes Video";

		$id = "empty-03.mp4";
		$this->videoMeta->$id = new stdClass();
		$this->videoMeta->$id->description = "Nochmal so irgendein Video";

		$id = "IMG_0894.mp4";
		$this->videoMeta->$id = new stdClass();
		$this->videoMeta->$id->description = "Trash us üsem Spielfilm";
	}


// interaction ////////////////////////////////////////////////////////////////

	public function getVideos(){
		$videos = Array();
		$paths = scandir( $this->videoPath );
		foreach( $paths AS $path ){
			$id = $file = basename( $path );
			if( $file=='.' || $file=='..' ) continue; // Ignore current and parent directory.
			$video = $this->createVideo( $id );
			array_push( $videos , $video );
		}
		return $videos;
	}

	public function getVideo( $id , $attachWriter=false ){
		$video = $this->createVideo( $id , true );
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			return $video;
		}else{
			return null;
		}
	}

	private function createVideo( $id , $attachWriter=false ){
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			$video = new stdClass();
			$video->id = $id;
			$ext = $this->fileHelper->getExtension( $file );
			$video->mime = $this->fileHelper->getMimeOfExtension( $ext );
			$video->description = $this->getVideoMeta( $id )->description;
			if( $attachWriter ){
				$video->writeTo = function( $oStream )use($file){
					$fd = fopen( $file , "r" );
					stream_copy_to_stream( $fd , $oStream );
					fclose( $fd );
				};
			}
			return $video;
		}else{
			return null;
		}
	}

	private function getVideoMeta( $id ){
		return isset($this->videoMeta->$id) ? $this->videoMeta->$id : null;
	}

}

