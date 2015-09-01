<?php // -->

namespace Api\Page\File;

use Modules\Helper;

class Image extends \Page 
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        return self::renderImage();
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
        $file = Raw::getData($uuid);
        if(empty($file)) {
            die('file not found');
        }

        // load the image object
        $image = eden('image', $file['path'], strtolower($file['extension']));
        // keep original ratio
        if(empty($dimension)) {
            Raw::dispose($file['path'], $file['name'], $file['mime'], $file['size']);
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