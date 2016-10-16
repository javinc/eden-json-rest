<?php //-->

namespace Controllers\File;

use Modules\Helper;
use Services\File;

class Raw
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    public static $auth = false;

    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function main()
    {
        $uuid = Helper::getSegment(0);

        // retrieve file
        if($data = File::getFile($uuid)) {
            return $data;
        }

        die('file not found');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
