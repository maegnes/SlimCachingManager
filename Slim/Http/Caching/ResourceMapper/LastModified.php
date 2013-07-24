<?php
// In work!
namespace Slim\Http\Caching\ResourceMapper;

class LastModified extends Base {

	public function setHeaders() {

		$this->_prepareResource();

		$res = $this->getHandler()->read( $this->_resource );

		if( $res instanceof \Slim\Http\Caching\IResource ) {

			// Set ETag
			$this->getApplication()->etag( $res->getEtag() );

			// Also set the "expires"-Header
			$this->getApplication()->expires( '+' . $res->getLifetime() . ' hours' );

		}

	}

}