<?php


require_once( "server/WriteToHelper.php" );


class VideoRepository {


// memory /////////////////////////////////////////////////////////////////////

	private $fileHelper;
	private $videoPath;
	private $videoMeta;


// initialization /////////////////////////////////////////////////////////////

	public function __construct( FileHelper $fileHelper , $videoPath ){
		$this->fileHelper = $fileHelper;
		$this->videoPath = $videoPath;
		$this->videoMeta = new stdClass();
	}


// interaction ////////////////////////////////////////////////////////////////

	public function getVideos(){
		/** Destination to store the collected videos to. */
		$videos = Array();
		/** List of files which can be videos. */
		$files = scandir( $this->videoPath );
		foreach( $files AS $id ){
			// Ensure that only flat paths are used.
			$id = basename( $id );
			if( $id=='.' || $id=='..' ){
				// Ignore current and parent directory.
			}else if( preg_match("/\.meta\.json$/i",$id) ){
				// Ignore meta files.
			}else if( preg_match("/\.thumb\.[^.]+$/",$id) ){
				// Ignore thumbnail files.
			}else{
				// This is actually a video file. Use it.
				array_push( $videos , $this->createVideo($id) );
			}
		}
		return $videos;
	}

	public function getVideo( $id ){
		$video = $this->createVideo( $id );
		if( file_exists($this->videoPath.$id) ){
			return $video;
		}else{
			return null;
		}
	}

	public function getThumbnail( $videoId ){
		$thumbFileName = $this->getThumbFileNameByVideoId( $videoId );
		$thumbPath = $this->videoPath . $thumbFileName;
		if( file_exists($thumbPath) ){
			$thumbnail = new WriteToHelper(function( $oStream , $start , $end )use($thumbPath){
				$fd = fopen( $thumbPath , "r" );
				stream_copy_to_stream( $fd , $oStream );
				fclose( $fd );
			});
			$thumbnail->mime = "image/jpeg"; // <- Currently only jpeg supported.
			return $thumbnail;
		}else{
			return null;
		}
	}

	/**
	 * Creates a object containing the information representing a video.
	 * @param id
	 * 		The id of the video to create
	 */
	private function createVideo( $id ){
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			$fileExtension = $this->fileHelper->getExtension( $file );
			$size = filesize($file);
			$video = new WriteToHelper(function( $oStream , $start , $end )use($file,$size){
				if( $end==-1 ) $end = $size;
				$fd = fopen( $file , "r" );
				stream_copy_to_stream( $fd , $oStream , $end-$start , $start );
				fclose( $fd );
			});
			$video->id = $id;
			$video->mime = $this->fileHelper->getMimeOfExtension( $fileExtension );
			$video->size = $size;
			$meta = $this->getVideoMeta( $id );
			$video->description = $meta ? $meta->description : null;
			// Thumbnail
			$thumbId = $this->getThumbFileNameByVideoId( $video->id );
			$video->thumb = new stdClass();
			$video->thumb->available = file_exists("{$this->videoPath}$thumbId");
			return $video;
		}else{
			return null;
		}
	}

	/**
	 * @return
	 * 		the filename of the meta file corresponding to the specified video id.
	 */
	private function getMetaFileNameByVideoId( $id ){
		return $this->getPureFileNameByVideoId( $id ) .".meta.json";
	}

	/**
	 * @return
	 * 		The filename of the thumb corresponding to the specified video id.
	 */
	private function getThumbFileNameByVideoId( $id ){
		return $this->getPureFileNameByVideoId($id) . ".thumb.jpg";
	}

	/**
	 * @return
	 * 		The filename without the extension.
	 */
	private function getPureFileNameByVideoId( $id ){
		preg_match( "/^(.*)\.[^.]+$/" , $id , $matches );
		return $matches[1];
	}

	/**
	 * Loads and caches the video meta data.
	 * @return
	 * 		The video meta to the provided video id or null if there is no meta
	 * 		available for this id.
	 */
	private function getVideoMeta( $id ){
		if( !isset($this->videoMeta->$id) ){
			// Initialize metadata from file.
			$fileName = $this->getMetaFileNameByVideoId( $id );
			$filePath = $this->videoPath . $fileName;
			if( file_exists($filePath) ){
				$this->videoMeta->$id = json_decode( file_get_contents($filePath) );
			}else{
				$this->videoMeta->$id = null;
			}
		}
		return $this->videoMeta->$id;
	}

}

