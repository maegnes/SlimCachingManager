<?php
/**
 * My own ResourceHandler for the usage with Slim Caching Manager
 *
 * Write and read cached resources to textfile
 *
 * @author Magnus Buk <Magnus.Buk@gmx.de>
 */
namespace Ezd\Caching;

class ResourceHandlerTextfile implements \Slim\Http\Caching\IResourceHandler {

    /**
     * Holds the cached resources
     *
     * @access public
     * @var array
     */
    protected $_data = Array();

    /**
     * @var null
     */
    private $_file = 'cache.txt';

    /**
     * Constructor. inject database connection and fetch caching data
     *
     * @access public
     */
    public function __construct() {
        $this->_readData();
    }

    /**
     * Call gc() on destruct
     *
     * @access public
     * @return void
     */
    public function __destruct() {
        $this->gc();
    }

    /**
     * Read data from database
     *
     * @access private
     * @return void
     */
    public function _readData() {

        if( !file_exists( $this->_file ) ) {
            $handle = fopen( $this->_file, 'a' );
            fwrite( $handle, 'a:0:{}' );
            fclose( $handle );
        }

        $this->_data = unserialize( file_get_contents( $this->_file ) );

    }

    /**
     * Read data for given resource
     *
     * @access public
     * @param String $resource
     * @return \Slim\Http\Caching\IResource
     */
    public function read( $resource = null ) {
        return ( is_array( $this->_data[$resource] ) ) ? $this->getObject( $this->_data[$resource] ) : null;
    }

    /**
     * Write resource to caching data
     *
     * @param $resource
     * @param $lifetime
     * @return bool
     */
    public function write( $resource, $lifetime ) {

        if( !array_key_exists( $resource, $this->_data ) ) {

            $this->_data[$resource] = Array(
                'resource' => $resource,
                'etag' => uniqid( 'ET' ),
                'lifetime' => $lifetime,
                'expiry_date' => date( 'Y-m-d H:i:s', strtotime( '+' . $lifetime . ' hours' ) )
            );

            file_put_contents( $this->_file, serialize( $this->_data ) );

            $this->_readData();

        }

        return true;

    }

    /**
     * Garbage collector - delete expired caching data
     *
     * @access public
     * @return bool
     */
    public function gc() {

        foreach( $this->_data as $resource => $data ) {
            if( strtotime( $data['expiry_date'] ) < time() )
                unset( $this->_data[$resource] );
        }

        file_put_contents( $this->_file, serialize( $this->_data ) );

    }

    /**
     * Returns the cached resource as a object
     *
     * @access public
     * @param array $res
     * @return Resource
     */
    public function getObject( $res = Array() ) {
        $obj = new \Slim\Http\Caching\Resource();
        $obj->setETag( $res['etag'] );
        $obj->setResource( $res['resource'] );
        $obj->setLifeTime( $res['lifetime'] );
        $obj->setExpiryDate( $res['clear_cache'] );
        return $obj;
    }
}