<?php

/**
 * DefaultController
 *
 * Default controller of ExifBundle
 *
 * @package		ExifBundle
 * @subpackage	Controller
 * @name        DefaultController
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 *
 */

namespace BiberLtd\Bundles\ExifBundle\Controller;

use BiberLtd\Bundles\ExifBundle\Services\Exif;
use BiberLtd\Core\CoreController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\Request;

class DefaultController extends CoreController {

    public function testAction(){
        $rootDir = $this->get('kernel')->getRootDir();
        $destination = $rootDir.'/../vendor/biberltd/exif-bundle/BiberLtd/Bundles/ExifBundle/Services/';
//        $exif = new Exif($destination.'kimlik.jpg');
        $exif = new Exif();
        $exif->setExifData('{"FILE":{"FileName":"1401704955_cbl_0216.jpg","FileDateTime":1401704955,"FileSize":747263,"FileType":2,"MimeType":"image/jpeg","SectionsFound":"ANY_TAG, IFD0, THUMBNAIL, EXIF"},"COMPUTED":{"html":"width=\"1280\" height=\"853\"","Height":853,"Width":1280,"IsColor":1,"ByteOrderMotorola":0,"ApertureFNumber":"f/4.0","Thumbnail.FileType":2,"Thumbnail.MimeType":"image/jpeg"},"IFD0":{"Make":"Canon","Model":"Canon EOS 5D Mark III","XResolution":"72/1","YResolution":"72/1","ResolutionUnit":2,"Software":"Adobe Photoshop Lightroom 5.0 (Windows)","DateTime":"2014:05:01 22:15:56","Artist":"Can Berkol","Exif_IFD_Pointer":238},"THUMBNAIL":{"Compression":6,"XResolution":"72/1","YResolution":"72/1","ResolutionUnit":2,"JPEGInterchangeFormat":892,"JPEGInterchangeFormatLength":14538},"EXIF":{"ExposureTime":"1/100","FNumber":"4/1","ExposureProgram":1,"ISOSpeedRatings":3200,"UndefinedTag:0x8830":2,"UndefinedTag:0x8832":3200,"ExifVersion":"0230","DateTimeOriginal":"2014:04:19 09:47:41","DateTimeDigitized":"2014:04:19 09:47:41","ShutterSpeedValue":"6643856/1000000","ApertureValue":"4/1","ExposureBiasValue":"0/1","MaxApertureValue":"4/1","MeteringMode":6,"Flash":16,"FocalLength":"35/1","SubSecTimeOriginal":"44","SubSecTimeDigitized":"44","ColorSpace":1,"FocalPlaneXResolution":"52428800/32768","FocalPlaneYResolution":"52428800/32768","FocalPlaneResolutionUnit":3,"CustomRendered":0,"ExposureMode":1,"WhiteBalance":0,"SceneCaptureType":0,"UndefinedTag:0xA431":"172028008766","UndefinedTag:0xA432":["24/1","105/1","0/0","0/0"],"UndefinedTag:0xA434":"EF24-105mm f/4L IS USM","UndefinedTag:0xA435":"0000454980"}}');
        $this->debug($exif->getLensInfo());
        $gps = $exif->getAllExifData();
        if ($gps) {
            $this->debug($gps);
        }
        echo 'allrayt'; die;
    }
}
