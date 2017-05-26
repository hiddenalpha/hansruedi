<?php


class WriteToHelper {


// memory /////////////////////////////////////////////////////////////////////

	private $writeToCallable;


// initialization /////////////////////////////////////////////////////////////

	public function __construct( $writeToCallable ){
		$this->writeToCallable = $writeToCallable;
	}


// interaction ////////////////////////////////////////////////////////////////

	/**
	 * Writes the data to the provided file descriptor.
	 * @param oStream
	 * 		The stream to write the data to.
	 * @param start
	 * 		The first byte to write.
	 * @param end
	 * 		The first byte not to write anymore. -1 means until end of file.
	 */
	public function writeTo( $oStream , $start=0 , $end=-1 ){
		$writeToCallable = $this->writeToCallable;
		return $writeToCallable( $oStream , $start , $end );
	}

}

