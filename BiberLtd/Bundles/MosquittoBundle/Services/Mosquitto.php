<?php
/**
 * Mosquitto Class
 *
 * @vendor      BiberLtd
 * @package		mosquitto-bundle
 *
 * @author      Can Berkol
 * @author      Said İmamoğlu
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        13.08.2014
 *
 */

namespace BiberLtd\Bundles\MosquittoBundle\Services;

use BiberLtd\Core\CoreModel;

class Mosquitto extends CoreModel{
    /**
     * @name        __construct()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     *
     */
    public function __construct(){
    }
    /**
     * @name        __init()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     *
     */
    public function init(){
        echo "Bundle initialized";
    }
}



/**
 * Change Log
 * **************************************
 * v1.0.0                   Said İmamoğlu
 * 13.08.2014
 * **************************************
 * A __construct()
 * A init()
 */