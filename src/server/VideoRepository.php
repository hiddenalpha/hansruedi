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
		$this->videoMeta = new stdClass();
	}


// interaction ////////////////////////////////////////////////////////////////

	public function getVideos(){
		$videos = Array();
		$thumbnails = Array();
		$files = scandir( $this->videoPath );
		// Collect thumbnails
		$thumbnailFiles = $files;
		$files = Array();
		foreach( $thumbnailFiles AS $id ){
			$id = basename( $id );
			if( $id=='.' || $id=='..' ){
				// Ignore current and parent directory.
			}else if( preg_match("/\.meta\.json$/i",$id) ){
				// Ignore meta files
			}else if( preg_match("/^(.*)\.thumb\.[^.]+$/",$id,$matches) ){
				$thumbMeta = new stdClass();
				$thumbMeta->id = $id;
				$thumbMeta->pureName = $matches[1];
				$thumbnails[ $thumbMeta->pureName ] = $thumbMeta;
			}else{
				// Not a thumbnail. Push to next array.
				array_push( $files , $id );
			}
		}
		// Create videos
		foreach( $files AS $id ){
			$id = basename( $id );
			// Evaluate corresponding thumbnail
			preg_match( "/^(.*)\.[^.]+$/" , $id , $matches );
			$thumbId = isset($thumbnails[$matches[1]]) ? $thumbnails[$matches[1]]->id : null;
			// Create video
			$video = $this->createVideo( $id , $thumbId );
			array_push( $videos , $video );
		}
		return $videos;
	}

	public function getVideo( $id , $attachWriter=false ){
		$video = $this->createVideo( $id , null , true );
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			return $video;
		}else{
			return null;
		}
	}

	public function getThumbnail( $videoId ){
		$thumbFileName = $this->getThumbFileNameByVideoId( $videoId );
		$thumbPath = $this->videoPath . $thumbFileName;
		if( file_exists($thumbPath) ){
			$thumbnail = new stdClass();
			$thumbnail->mime = "image/jpeg";
			$thumbnail->writeTo = function( $oStream )use($thumbPath){
				$fd = fopen( $thumbPath , "r" );
				stream_copy_to_stream( $fd , $oStream );
				fclose( $fd );
			};
			return $thumbnail;
		}else{
			return null;
		}
	}

	private function createVideo( $id , $thumbId , $attachWriter=false ){
		$file = $this->videoPath . $id;
		if( file_exists($file) ){
			$fileExtension = $this->fileHelper->getExtension( $file );
			// Basic
			$video = new stdClass();
			$video->id = $id;
			$video->mime = $this->fileHelper->getMimeOfExtension( $fileExtension );
			$video->description = $this->getVideoMeta( $id )->description;
			// Thumbnail
			$thumbId = $this->getThumbFileNameByVideoId( $video->id );
			$video->thumb = new stdClass();
			$video->thumb->available = file_exists("{$this->videoPath}$thumbId");
			// writer
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

	private function getMetaByVideoId( $id ){
		$fileName = $this->getMetaFileNameByVideoId( $id );
		$filePath = $this->videoPath . $fileName;
		if( file_exists($filePath) ){
			return json_decode( file_get_contents($filePath) );
		}else{
			return null;
		}
	}

	private function getMetaFileNameByVideoId( $id ){
		return $this->getPureFileNameByVideoId( $id ) .".meta.json";
	}

	private function getThumbFileNameByVideoId( $id ){
		return $this->getPureFileNameByVideoId($id) . ".thumb.jpg";
	}

	/** @return The filename without the extension. */
	private function getPureFileNameByVideoId( $id ){
		preg_match( "/^(.*)\.[^.]+$/" , $id , $matches );
		return $matches[1];
	}

	private function getVideoMeta( $id ){
		if( !isset($this->videoMeta->$id) ){
			// Initialize metadata
			$this->videoMeta->$id = $this->getMetaByVideoId( $id );
		}
		return $this->videoMeta->$id;
	}

}

