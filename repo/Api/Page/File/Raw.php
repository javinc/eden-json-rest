<?php //-->

namespace Api\Page\File;

use Modules\Helper;
use Resources\File as F;

class Raw extends \Page 
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
        $uuid = Helper::getSegment(0);
        
        // retrieve file
        if($data = self::getFile($uuid)) {
            return $data;
        }

        die('file not found');
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
        $data['path'] = Upload::getPath() . '/' . $uuid . '.' . $data['extension'];

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

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}