<?php
namespace Slim\Http\Caching;

class ResourceHandler implements IResourceHandler {

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
     * Read data from database
     *
     * @access private
     * @return void
     */
    private function _readData() {
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
     * @return mixed
     */
    public function read( $resource = null ) {
        return ( is_array( $this->_data[$resource] ) ) ? $this->_data[$resource] : null;
    }

    /**
     * Write resource to caching data
     *
     * @param $resource
     * @param $lifetime
     * @return bool
     */
    public function write( $resource, $lifetime ) {

    }

    /**
     * Garbage collector - delete expired caching data
     *
     * @access public
     * @return bool
     */
    public function gc() {

    }

}