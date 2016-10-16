<?php //-->

namespace Controllers\File;

use Modules\Helper;
use Services\File;

class Upload
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function main()
    {
        // upload & check if key is upload
        if(Helper::getRequestMethod() == 'POST') {
            $files = Helper::getFile();
            if(empty($files)) {
                return Helper::error(
                    'FILE_UPLOAD_EMPTY',
                    'no file to be uploaded');
            }

            // get file input
            $file = current($files);

            return File::upload($file);
        }

        return Helper::error(
            'METHOD_NOT_ALLOWED',
            'method not allowed');
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
