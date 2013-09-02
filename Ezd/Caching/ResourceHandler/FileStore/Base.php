<?php
/**
 * Base class for the Filestore ResourceHandlers
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
namespace Ezd\Caching\ResourceHandler\FileStore;

use \Slim\Http\Caching as SlimCaching;

abstract class Base {

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
    protected $_file = 'cache.json';

    /**
     * Constructor. inject json file (optional)
     *
     * @param String $file
     */
    public function __construct( $file = null ) {
        if( !is_null( $file ) )
            $this->_file = $file;
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
            fwrite( $handle, $this->writeFormat( Array() ) );
            fclose( $handle );
        }

        $this->_data = $this->readFormat( file_get_contents( $this->_file ) );

    }

    /**
     * Read data for given resource
     *
     * @access public
     * @param String $resource
     * @return \Slim\Http\Caching\IResource
     */
    public function read( $resource = null ) {
        return ( array_key_exists( $resource, $this->_data ) ) ? $this->getObject( $this->_data[$resource] ) : null;
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
                'expiry_date' => date( 'Y-m-d H:i:s', strtotime( '+' . $lifetime . ' hours' ) ),
                'clear_cache' => date( 'Y-m-d H:i:s', strtotime( '+' . $lifetime . ' hours' ) ),
                'last_modified' => date( 'Y-m-d H:i:s', time() )
            );

            file_put_contents( $this->_file, $this->writeFormat( $this->_data ) );

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

        file_put_contents( $this->_file, $this->writeFormat( $this->_data ) );

    }

    /**
     * Returns the cached resource as a object
     *
     * @access public
     * @param array $res
     * @return Resource
     */
    public function getObject( $res = Array() ) {
        $obj = new SlimCaching\Resource();
        $obj->setETag( $res['etag'] );
        $obj->setResource( $res['resource'] );
        $obj->setLifeTime( $res['lifetime'] );
        $obj->setExpiryDate( $res['clear_cache'] );
        $obj->setLastModified( $res['last_modified'] );
        return $obj;
    }

    /**
     * Write the text based data as json
     *
     * @param $data
     * @return string
     */
    public abstract function writeFormat( $data );

    /**
     * Read the text based stored data as json
     *
     * @param $data
     * @return mixed
     */
    public abstract function readFormat( $data );

}