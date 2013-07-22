SlimCachingManager
==================

Usage
--------
1. Create instance of Slim
2. Add resources to the ResourceMapper which should be cached (Wildcards allowed!)
3. Create instance of Slim\Http\Caching\ResourceMapper();
4. Inject your own ResourceHandler into ResourceMapper
5. Inject the Slim application into ResourceMapper
6. Call method setHeaders()

Example
--------
	<?php
	
	$app = new \Slim\Slim();
	
	// Add resource '/fetch/my/resource/' to my cache list
	Slim\Http\Caching\ResourceMapper::addResourceWildcard( '/fetch/my/resource/', 24 );
	
	# Create instance of the ResourceMapper
	$cachingManager = new Slim\Http\Caching\ResourceMapper();
	
    $cachingManager->setHandler( new Ezd\Caching\ResourceHandler( $db ) )
        ->setApplication( $app )
        ->setHeaders();

	?>
	
That's it. ETag and Expires will be set automatically.

Have fun!