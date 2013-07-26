<?php
/**
 * My own ResourceHandler for the usage with Slim Caching Manager
 *
 * Write and read cached resources to database
 *
 * @author Magnus Buk <Magnus.Buk@gmx.de>
 */
namespace Ezd\Caching;

class ResourceHandler implements \Slim\Http\Caching\IResourceHandler {

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
    private $_db = null;

    /**
     * Constructor. inject database connection and fetch caching data
     *
     * @access public
     */
    public function __construct( $db = null) {
        $this->_db = $db;
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
        $query = $this->_db->getDb()->execute( "SELECT * FROM api_cache_lifetime" );
        while( $res = $query->fetchRow() ) {
            $this->_data[$res['resource']] = $res;
        }
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

            $this->_db->getDb()->execute(
                "EXEC usp_app_generate_cache_resource '{$resource}', {$lifetime}"
            );

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
        # Done by cronjobs
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
        $obj->setLastModified( $res['last_modified'] );
        return $obj;
    }
}