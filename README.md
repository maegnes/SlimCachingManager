SlimCachingManager
==================

If you want to outsource the lifetime of your resource caches you can use the SlimCachingManager. It allows you to add resources (wildcard possible) to your application. SlimCachingManager will add the resources to your own data adapter (e.g. Database).

The storage (ResourceHandler) stores the data for resource, etag, lifetime and expiry date and will set the HTTP headers automatically. If the resource is out of date you can use the gc() method
tgo rebuild the cache.

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
	
	# Create Slim instance
	$app = new \Slim\Slim();
	
	# Add resource '/fetch/my/resource/' to my cache list
	Slim\Http\Caching\ResourceMapper::addResourceWildcard( '/fetch/my/resource/', 24 );
	
	# If you want to cache more than one resource
	Slim\Http\Caching\ResourceMapper::addResourceWildcard(
		Array(
			'/fetch/my/resource/' => 24,
			'/fetch/my/second/resource/' => 5,
			'/fetch/products/list/' => 1
		)
	);
	
	# Create instance of the ResourceMapper
	$cachingManager = new Slim\Http\Caching\ResourceMapper();
	
	# Inject Handler and Slim Application and set Headers
    $cachingManager->setHandler( new Ezd\Caching\ResourceHandler( $db ) )
        ->setApplication( $app )
        ->setHeaders();

	?>
	
That's it. ETag and Expires will be set automatically.

Have fun!