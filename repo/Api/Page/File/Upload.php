<?php //-->

namespace Api\Page\File;

use Modules\Helper;
use Modules\Upload as U;
use Resources\File as RF;

class Upload extends \Page 
{
    /* Constants
    --------------------------------------------*/
    const UPLOAD_KEY = 'file';

    /* Public Properties
    --------------------------------------------*/
    protected static $filePath = 'upload';

    /* Protected Properties
    --------------------------------------------*/
    protected static $allowedMime = array(
        'image/jpeg', 
        'image/png');

    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
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

            return self::upload($file);
        }

        return Helper::error(
            'METHOD_NOT_ALLOWED',
            'method not allowed');
    }

    public static function getPath() {
        return control()->path(self::$filePath);
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function upload($file)
    {
        $path = self::getPath();

        // init Upload
        $upload = new U();
        $upload->setPath($path)->setAllowedMime(self::$allowedMime);

        // uploading
        try {
            $data = $upload->process($file);

            return RF::create(array(
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
}