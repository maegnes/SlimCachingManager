SlimCachingManager
==================

Works under https://github.com/codeguy/Slim >= ~2.3 (>= 2.3 AND < 3.0)

The **SlimCachingManager** helps you to simplify caching and the delivery of the cached data of the Slim Framework resources. It stores the caching data for each resource (etag, expiry, lastmodified etc.) in ResourceHandlers
which can be written on your own by implementing the *IResourceHandler*.

It's helpful for resources which doesn't have any physical changes to detect that the resource has been changed (e.g. database query results). It's possible to define resources (wildcard notation possible) which you'd like to have cached. SlimCachingManager will
automatically check if the resource should or is being cached at the moment. The rest is quite simple.

You want to use **SlimCachingManager**? Just follow the steps at the bottom of the page. All your headers (Etag, Lastmodified, expiry) will be set by **SlimCachingManager**.

If you have any questions or suggestions to improve **SlimCachingManager** feel free to contact me. If you want to contribute just add a new branch and send me a pull request.

Best regards
Magnus

Installation via Composer
--------
Add the following dependency to your composer.json file.

    {
        "require": {
			"maegnes/slim-caching-manager": "dev-master"
        }
    }

Usage
--------
1. Create instance of Slim
2. Add resources to the ResourceMapper which should be cached (Wildcards allowed!)
3. Add a before.dispatch hook or middleware (in the example it's done by hook)
4. Create instance of SlimCachingManager\ResourceMapper\Etag() or SlimCachingManager\ResourceMapper\Lastmodified() (depends on needed cache method)
5. Inject your the ResourceHandler into ResourceMapper
6. Inject the Slim application into ResourceMapper
7. Call method setHeaders()

Example
--------
	<?php
	
	# 1. Create Slim instance
	$app = new Slim();
	
	# 2a. Add resource '/fetch/my/resource/' to my cache list
	\SlimCachingManager\ResourceMapper\Base::addResourceWildcard('/fetch/my/resource/', 24);

	# 2b. It's also possible to pass a Array with the resource wildcards
	\SlimCachingManager\ResourceMapper\Base::addResourceWildcard(
		Array(
			// Moderator data and list
			'/fetch/moderator/data/' => 24,
			'/fetch/moderator/list/' => 24,
		)
	);	
	
	# 3. You could also use a middleware. For that simple example i used a before.dispatch hook.
	$app->hook('slim.before.dispatch', function () use ($app, $db) {

		# 4. Create instance of your wished ResourceMapper (ETag() or Lastmodified())
		$cachingManager = new \SlimCachingManager\ResourceMapper\ETag();

		# 5. Inject your ResourceHandler, Slim instance and finally set the headers
		$cachingManager->setHandler(new \SlimCachingManager\ResourceHandler\Textfile())
			->setApplication($app) #6.
			->setHeaders(); #7.

	});

	?>

If you don't want to use the "Textfile" Resourcehandler just write your own by implementing
**SlimCachingManager\ResourceHandler\IResourceHandler**!

Have fun!
