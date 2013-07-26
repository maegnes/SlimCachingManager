<?php
// In work!
namespace Slim\Http\Caching\ResourceMapper;

class LastModified extends Base {

	public function setHeaders() {

		$this->_prepareResource();

		$res = $this->getHandler()->read( $this->_resource );

		if( $res instanceof \Slim\Http\Caching\IResource ) {

			// Set Last Modified date
			$this->getApplication()->lastModified( $res->getLastModified() );

		}

	}

}