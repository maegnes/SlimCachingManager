Write your own ResourceHandler
==================

If you want to write your own *ResourceHandler* for the **SlimCachingManager** just write a class in a namespace of your choice and implement *\Slim\Http\Caching\IResourceHandler*.

The following data should be stored for each resource to your data adapter:

1. resource (String)
2. etag (String)
3. lifetime (int)
4. expiry_date (datetime)
5. last_modified (datetime) (just needed if you want to have lastmodified-caching)

I've added two examples for ResourceHandlers.

1. **ResourceHandler.php**: Stores the data in a database
2. **ResourceHandlerTextfile.php**: Stores the serialized data in a textfile. (not recommended for production!)