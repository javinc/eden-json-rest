<?php //-->

namespace Services;

use Resources\File as F;
use Modules\Helper;
use Modules\Upload;

class File
{
    /* Constants
    --------------------------------------------*/
    const UPLOAD_KEY = 'file';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $filePath = 'upload';
    protected static $allowedMime = array(
            'image/jpeg', 
            'image/png');

    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function __callStatic($name, $args)
    {   
        return F::$name(current($args), end($args));
    }

    public static function getData($uuid)
    {
        // search uuid if exists
        $data = F::get(array(
            'filters' => array(
                'uuid' => $uuid)));
        
        // check empty
        if(empty($data)) {
            return false;
        } 

        // add file path
        $data['path'] = self::getPath() . '/' . $uuid . '.' . $data['extension'];

        return $data;
    }

    public static function getFile($uuid)
    {   
        $data = self::getData($uuid);
        // check if empty
        if(empty($data)) {
            return false;
        }

        // dispose file
        if($data) {
            self::dispose($data['path'], $data['name'], $data['mime'], $data['size']);
        }

        return false;
    }

    public static function dispose($path, $name, $mime, $size)
    {
        $fp = fopen($path, 'rb');

        // send the right headers
        header('Content-Disposition: inline; filename="' . $name . '"');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);

        // dump the picture and stop the script
        fpassthru($fp);

        exit;
    }

    public static function getPath() {
        return control()->path(self::$filePath);
    }

    public static function upload($file)
    {
        $path = self::getPath();

        // init Upload
        $upload = new Upload();
        $upload->setPath($path)->setAllowedMime(self::$allowedMime);

        // uploading
        try {
            $data = $upload->process($file);

            return F::create(array(
                'uuid' => $data['uuid'],
                'name' => $data['meta']['name'],
                'extension' => $data['extension'],
                'mime' => $data['meta']['type'],
                'size' => $data['meta']['size']));
        } catch (Exception $e) {
            return Helper::error(
                'FILE_UPLOAD_ERROR',
                $e->getMessage());
        }

        return $result;
    }

    public static function renderImage()
    {
        // variables=0 is the dimension
        $segment = Helper::getSegment();
        $uuid = $segment[0];
        $dimension = $segment[1];

        //  check if param complete
        if(count($segment) == 0) {
            die('invalid parameters');
        }

        // http://host.com/image/400x200/ed9b3d2e9c84f59c513bb0e5081f0945
        if(($tmp = explode('x', $dimension)) && sizeof($tmp) > 1){
            $dim_w = intval($tmp[0]);
            $dim_h = intval($tmp[1]);
        }
        
        // get image path
        $file = self::getData($uuid);
        if(empty($file)) {
            die('file not found');
        }

        // load the image object
        $image = eden('image', $file['path'], strtolower($file['extension']));
        // keep original ratio
        if(empty($dimension)) {
            self::dispose($file['path'], $file['name'], $file['mime'], $file['size']);
        // parameter passed is one (http://host.com/image/100/ed9b3d2e9c84f59c513bb0e5081f0945)
        } else if($dimension > 0 && !isset($dim_h)) {
            // resize the image
            $image->resize(null, $dimension);
            // crop the image
            $image->crop($dimension, $dimension);
        // parameter passed is for width and height (http://host.com/image/300x100/ed9b3d2e9c84f59c513bb0e5081f0945)
        } else if($dimension > 0 && isset($dim_h)) {
            // scale the image to fit specific dimensions
            $image->scale($dim_w, $dim_h);
            // crop the image
            $image->crop($dim_w, $dim_h);           
        }

        header('Content-type: image/' . $file['extension']);
        die($image);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}