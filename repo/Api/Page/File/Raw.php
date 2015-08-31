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
        if(Helper::getRequestMethod() == 'GET') {
            // if key is uuid impose raw file
            if($data = self::getRawFile($uuid)) {
                return $data;
            }
        }

        return Helper::error(
            'FILE_NOT_FOUND',
            $key . ' not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function getRawFile($uuid)
    {   
        // search uuid if exists
        $data = F::get(array(
            'filters' => array(
                'uuid' => $uuid)));

        // dispose file
        if($data) {
            $path = Upload::getPath() . '/' . $uuid . '.' . $data['extension'];
            $fp = fopen($path, 'rb');

            // send the right headers
            header('Content-Disposition: inline; filename="' . $data['name'] . '"');
            header('Content-Type: ' . $data['mime']);
            header('Content-Length: ' . $data['size']);

            // dump the picture and stop the script
            fpassthru($fp);

            exit;
        }

        return false;
    }
}