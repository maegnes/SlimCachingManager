SlimCachingManager
==================

Usage
--------
(1) Create instance of Slim
(2) Add resources to the ResourceMapper which should be cached (Wildcards allowed!)
(3) Create instance of Slim\Http\Caching\ResourceMapper();
(4) Inject your own ResourceHandler into ResourceMapper
(5) Inject the Slim application into ResourceMapper
(6) Call method setHeaders()

Example
--------
<code>
<?php

echo "TEST";

?>
</code>