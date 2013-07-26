SlimCachingManager
==================

Works under https://github.com/codeguy/Slim 2.3.0

The **SlimCachingManager** helps you to simlpify caching and the delivery of the cached data of the Slim Framework resources. It stores the caching data for each resource (etag, expiry, lastmodified) in ResourceHandlers
which can be written on your own by implementing the *IResourceHandler*.

It's helpful for resources which doesn't have any physical changes to detect that the resource has been changed (e.g. database query results). It's possible to define resources (wildcard notation possible) which you'd like to be cached. SlimCachingManager will
automatically check if the resource should or is being cached at the moment. The rest is just amazement.

You want to use SlimCaching Manager? Just follow the steps at the bottom of the page. All your headers (Etag, Lastmodified, expiry) will be set by SlimCachingManager.

If you have any questions or suggestions to improve **SlimCachingManager** feel free to contact me. If you want to contribute just add a new branch and send me a pull request.

Best regards
Magnus

Usage
--------
1. Create instance of Slim
2. Add resources to the ResourceMapper which should be cached (Wildcards allowed!)
3. Add a before.dispatch hook or middleware (in the example it's done by hook)
4. Create instance of Slim\Http\Caching\ResourceMapper\Etag() or Slim\Http\Caching\ResourceMapper\Lastmodified() (depends on needed cache method)
5. Inject your own ResourceHandler into ResourceMapper
6. Inject the Slim application into ResourceMapper
7. Call method setHeaders()

Example
--------
	<?php
	
	# Create Slim instance
	$app = new \Slim\Slim();
	
	# Add resource '/fetch/my/resource/' to my cache list
	Slim\Http\Caching\ResourceMapper\Base::addResourceWildcard( '/fetch/my/resource/', 24 );

	# It's also possible to pass a Array with the resource wildcards
	Slim\Http\Caching\ResourceMapper\Base::addResourceWildcard(
		Array(
			// Moderator data and list
			'/fetch/moderator/data/' => 24,
			'/fetch/moderator/list/' => 24,
		)
	);	
	
	$app->hook( 'slim.before.dispatch', function () use ( $app, $db ) {

		$cachingManager = new Slim\Http\Caching\ResourceMapper\Etag();

		$cachingManager->setHandler( new Ezd\Caching\ResourceHandler( $db ) )
			->setApplication( $app )
			->setHeaders();

	});

	?>
	
That's it. ETag and Expires will be set automatically.

Have fun!