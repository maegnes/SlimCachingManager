<?php
/**
 * My own ResourceHandler for the usage with Slim Caching Manager
 *
 * Write and read cached resources to textfile
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
namespace Ezd\Caching\ResourceHandler\FileStore;

use \Slim\Http\Caching as SlimCaching;

class Serialized extends Base implements \Slim\Http\Caching\IResourceHandler, SlimCaching\IFileStore {

    /**
     * @var null
     */
    protected $_file = 'cache.serialized';

    /**
     * Write the text based data as serialized string
     *
     * @param $data
     * @return string
     */
    public function writeFormat( $data ) {
        return serialize( $data );
    }

    /**
     * Read the text based stored data as serialized string
     *
     * @param $data
     * @return mixed
     */
    public function readFormat( $data ) {
        return unserialize( $data );
    }
}