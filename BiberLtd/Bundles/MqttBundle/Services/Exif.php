<?php
/**
 * Exif Class
 *
 * @vendor      BiberLtd
 * @package		exif-bundle
 *
 * @author      Can Berkol
 * @author      Said İmamoğlu
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.2
 * @date        08.06.2014
 *
 *
 * @todo Said İmamoğlu
 * A getCopyright()
 * A setCopyRight()
 */

namespace BiberLtd\Bundles\ExifBundle\Services;

use BiberLtd\Core\CoreModel;
use Symfony\Component\Filesystem\Filesystem;

class Exif extends CoreModel{
    /**
     * @var        file
     */
    public $file;
    /**
     * @var        exif
     */
    public $exif;
    /**
     * @name        __construct()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.1
     *
     * @param       $file
     *
     */
    public function __construct($file = null){
        if (!is_null($file)) {
            $fileSystem = new Filesystem();
            if(!$fileSystem->exists($file)){
                exit('File not found');
            }
            $this->exif = exif_read_data($file, 'ANY_TAG', true);
            unset($file);
        }
    }
    /**
     * @name        setFile()
     *
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @param       $file
     *
     */
    public function setFile($file){
        if (!is_null($file)) {
            $this->file = $file;
        }
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->file)) {
            exit('File not found');
        }
        $this->exif = exif_read_data($this->file, 'ANY_TAG', true);
        $this->fixUndefinedTags();
        unset($file);
    }
    /**
     * @name        getComputedTag()
     *
     * @author      Can Berkol
     * @since       1.0.2
     * @version     1.0.2
     *
     * @return array or bool
     */
    public function getComputedTag(){
        if (!$this->exif ||!isset($this->exif['COMPUTED'])) {
            return false;
        }
        return $this->exif['COMPUTED'];
    }
    /**
     * @name        getIFDOTag()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getIFDOTag(){
        if (!$this->exif || !isset($this->exif['IFD0'])) {
            return false;
        }
        return $this->exif['IFD0'];
    }
    /**
     * @name        getEXIFTag()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getEXIFTag(){
        if (!$this->exif || !isset($this->exif['EXIF'])) {
            return false;
        }
        return $this->exif['EXIF'];
    }
    /**
     * @name        getFileTag()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getFileTag(){
        if (!$this->exif || !isset($this->exif['FILE'])) {
            return false;
        }
        return $this->exif['FILE'];
    }
    /**
     * @name        readGPSinfo()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function readGPSinfo()
    {
        $gps = $this->exif;
        if(!$gps || !isset($this->exif['GPS']) || $this->exif['GPS']['GPSLatitude'] == '') {
            return false;
        } else {
            $lat_ref = $gps['GPS']['GPSLatitudeRef'];
            $lat = $gps['GPS']['GPSLatitude'];
            list($num, $dec) = explode('/', $lat[0]);
            $lat_s = $num / $dec;
            list($num, $dec) = explode('/', $lat[1]);
            $lat_m = $num / $dec;
            list($num, $dec) = explode('/', $lat[2]);
            $lat_v = $num / $dec;

            $lon_ref = $gps['GPS']['GPSLongitudeRef'];
            $lon = $gps['GPS']['GPSLongitude'];
            list($num, $dec) = explode('/', $lon[0]);
            $lon_s = $num / $dec;
            list($num, $dec) = explode('/', $lon[1]);
            $lon_m = $num / $dec;
            list($num, $dec) = explode('/', $lon[2]);
            $lon_v = $num / $dec;

            $gps_int = array('lat'=>$lat_s + $lat_m / 60.0 + $lat_v / 3600.0, 'lon'=>$lon_s
            + $lon_m / 60.0 + $lon_v / 3600.0);
            return $gps_int;
        }
    }
    /**
     * @name        getGPSLat()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or string
     */
    public function getGPSLat(){
        $gps = $this->readGPSinfo();
        if (!isset($gps) || isset($gps['lat'])) {
            return null;
        }
        return $gps['lat'];
    }
    /**
     * @name        getGPSLon()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or string
     */
    public function getGPSLon(){
        $gps = $this->readGPSinfo();
        if (!isset($gps) || isset($gps['lon'])) {
            return null;
        }
        return $gps['lon'];
    }
    /**
     * @name        getAllExifData()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return  exif
     */
    public function getAllExifData(){
        return $this->exif;
    }
    /**
     * @name        getMegaPixels()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getMegaPixels(){
        $ifd0 = $this->getIFDOTag();
        if (!$ifd0) {
            return false;
        }
        if (isset($ifd0['XResolution']) && isset($ifd0['YResolution'])) {
            return array($ifd0['XResolution'],$ifd0['YResolution']);
        }
        
    }
    /**
     * @name        getMP()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getMP(){
        return $this->getMegaPixels();
    }
    /**
     * @name        getCameraMake()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getCameraMake(){
        $ifd0 = $this->getIFDOTag();
        if (!$ifd0 || isset($ifd0['Make'])) {
            return false;
        }
        return $ifd0['Make'];
    }
    /**
     * @name        getCameraModel()
     *
     * @author      Can Berkol
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getCameraModel(){
        $ifd0 = $this->getIFDOTag();
        if (!$ifd0 || !isset($ifd0['Model'])) {
            return false;
        }
        return $ifd0['Model'];
    }
    /**
     * @name        getAperture()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getAperture(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['ApertureValue'])) {
            return false;
        }
        return $exif['ApertureValue'];
    }
    /**
     * @name        getFNumber()
     *
     * @author      Can Berkol
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.2
     *
     * @return array or bool
     */
    public function  getFNumber(){
        $exif = $this->getComputedTag();
        if (!$exif || !isset($exif['ApertureFNumber'])) {
            return false;
        }
        return $exif['ApertureFNumber'];
    }
    /**
     * @name        getExposureTime()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getExposureTime(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['ExposureTime'])) {
            return false;
        }
        return $exif['ExposureTime'];
    }
    /**
     * @name        getIsoSpeed()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getIsoSpeed(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['ISOSpeedRatings'])) {
            return false;
        }
        return $exif['ISOSpeedRatings'];
    }
    /**
     * @name        dumpGPSInfo()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function dumpGPSInfo(){
        return $this->readGPSinfo;
    }
    /**
     * @name        getDateTaken()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return string
     */
    public function getDateTaken(){
        $exif = $this->getEXIFTag();
        if (!$exif) {
            $ifd0 = $this->getIFDOTag();
            if (!$ifd0) {
                $file = $this->getFILETag();
                if (!$file) {
                    return null;
                } else{
                    if (isset($file['FileDateTime'])) {
                        return $file['FileDateTime'];
                    }
                }
            } else{
                if (isset($ifd0['DateTime'])) {
                    return $ifd0['DateTime'];
                } else{
                    return null;
                }
            }
        } else{
            if (isset($exif['DateTimeOriginal'])) {
                return $exif['DateTimeOriginal'];
            } else{
                if (isset($exif['DateTimeDigitized'])) {
                    return $exif['DateTimeDigitized'];
                } else{
                    return null;
                }
            }
        }
    }
    /**
     * @name        getExifInfo()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getExifInfo(){
        return $this->getEXIFTag();
    }
    /**
     * @name        getShutterSpeed()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getShutterSpeed(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['ShutterSpeedValue'])) {
            return false;
        }
        return $exif['ShutterSpeedValue'];
    }
    /**
     * @name        getFocalLength()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function getFocalLength(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['FocalLength'])) {
            return false;
        }
        return $exif['FocalLength'];
    }
    /**
     * @name        get35mmFocalLength()
     *
     * @author      Said İmamoğlu
     * @since       1.0.0
     * @version     1.0.0
     *
     * @return array or bool
     */
    public function get35mmFocalLength(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['FocalLengthIn35mmFilm'])) {
            return false;
        }
        return $exif['FocalLengthIn35mmFilm'];
    }
    /**
     * @name        dumpLensInfo()
     *
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @return array or bool
     */
    public function dumpLensInfo(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['LensInfo'])) {
            return false;
        }
        return $exif['LensInfo'];
    }
    /**
     * @name        getCameraSerial()
     *
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @return array or bool
     */
    public function getCameraSerial(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['CameraSerial'])) {
            return false;
        }
        return $exif['CameraSerial'];
    }
    /**
     * @name        getLensModel()
     *
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @return array or bool
     */
    public function getLensModel(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['LensModel'])) {
            return false;
        }
        return $exif['LensModel'];
    }
    /**
     * @name        getLensSerial()
     *
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @return array or bool
     */
    public function getLensSerial(){
        $exif = $this->getEXIFTag();
        if (!$exif || !isset($exif['LensSerial'])) {
            return false;
        }
        return $exif['LensSerial'];
    }
    /**
     * @name        setExifData()
     *
     * @author      Can Berkol
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.1
     *
     * @return array or bool
     */
    public function setExifData($exifData = array()){
        if (!is_array($exifData)) {
            if (!is_object($exifData)) {
                //convert to array
                $this->exif = json_decode($exifData,true);
            }
        }
        $this->fixUndefinedTags();
        return $this->exif;
    }
    /**
     * @name        fixUndefinedTags()
     *              Fixes undefined tags
     *
     * @author      Can Berkol
     * @author      Said İmamoğlu
     * @since       1.0.1
     * @version     1.0.2
     *
     * @return array or bool
     */
    public function fixUndefinedTags(){
        //Write 0xA434 tag number label
        $undefinedTags = array(
            '0xA431' => 'CameraSerial',
            '0xA432' => 'LensInfo',
            '0xA433' => 'LensMake',
            '0xA434' => 'LensModel',
            '0xA435' => 'LensSerial',
        );
        if($this->exif && isset($this->exif['EXIF'])){
            foreach ($this->exif['EXIF']  as $key=>$value) {
                foreach ($undefinedTags as $tagKey => $tag) {
                    if (strpos($key, $tagKey)) {
                        if (isset($this->exif['EXIF'])) {
                            $this->exif['EXIF'][$tag] = $value;
                            unset($this->exif['EXIF'][$key]);
                        }
                    }
                }
            }
        }
    }
}



/**
 * Change Log
 * **************************************
 * v1.0.2                      Can Berkol
 * 08.06.2014
 * **************************************
 * A getComputedTag()
 * B getCameraModel()
 * B getExifTag()
 * B getFID0Tag()
 * B setExifData()
 * U fixUndefinedTags()
 * U getFNumber()
 *
 * **************************************
 * v1.0.1                   Said İmamoğlu
 * 02.06.2014
 * **************************************
 * A setExifData()
 * A getLensInfo()
 * U __construct()
 * A getLensInfo()
 * A getLensSerial()
 * A getLensMake()
 * A getLensModel()
 * A getCameraSerial()
 * **************************************
 * v1.0.0                   Said İmamoğlu
 * 06.05.2014
 * **************************************
 * A getCameraMake()
 * A getCameraModel()
 * A getMegaPixels()
 * A getMP()
 * A getAperture()
 * A getFNumber()
 * A getExposureTime()
 * A getIsoSpeed()
 * A dumpGPSInfo()
 * A getGPSLat()
 * A getDateTaken()
 * A getExifInfo()
 * A setDateTaken()
 * A getShutterSpeed()
 * A getFocalLength()
 * A get35mmFocalLength()
 * A getGPSLon()
 */