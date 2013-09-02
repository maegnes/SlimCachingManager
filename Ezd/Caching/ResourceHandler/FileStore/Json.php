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

class Json extends Base implements SlimCaching\IResourceHandler, SlimCaching\IFileStore {

    /**
     * @var String
     */
    protected $_file = 'cache.json';

    /**
     * Write the text based data as json
     *
     * @param $data
     * @return string
     */
    public function writeFormat( $data ) {
        return json_encode( $data );
    }

    /**
     * Read the text based stored data as json
     *
     * @param $data
     * @return mixed
     */
    public function readFormat( $data ) {
        return json_decode( $data, true );
    }
}