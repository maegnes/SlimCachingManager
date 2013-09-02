Write your own ResourceHandler
==================

If you want to write your own *ResourceHandler* for the **SlimCachingManager** just write a class in a namespace of your choice and implement *\Slim\Http\Caching\IResourceHandler*.
If you want to have a file based resource handler, your class should additionally implement *Slim\Http\Caching\IFileStore.php* to provide methods to read and write data from files (e.g. json_encode(), serialize() etc.).

The following data should be stored in your resource handlers for each resource.

1. resource (String)
2. etag (String)
3. lifetime (int)
4. expiry_date (datetime)
5. last_modified (datetime) (just needed if you want to have lastmodified-caching)

I've added three **ResourceHandler** Examples.

1. **Ezd\Caching\ResourceHandler\Database.php**: Stores the data in a database
2. **Ezd\Caching\ResourceHandler\FileStore\Json.php**: Stores the json encoded data in a textfile.
3. **Ezd\Caching\ResourceHandler\FileStore\Serialized.php**: Stores the serialized data in a textfile.