<?php //-->

namespace Api\Page;

use Exception;
use Modules\Auth;
use Modules\Helper;
use Modules\Upload;
use Resources\File as F;

class File extends \Page 
{
    /* Constants
    --------------------------------------------*/
    const UPLOAD_KEY = 'upload';

    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    protected static $filePath = 'upload';
    protected static $allowedMime = array(
        'image/jpeg', 
        'image/png');

    /* Public Methods
    --------------------------------------------*/
    public function getVariables()
    {   
        $method = Helper::getRequestMethod();
        $key = Helper::getSegment(0);

        // upload & check if key is upload
        if($method == 'POST' && $key == self::UPLOAD_KEY) {
            // get file input
            return self::upload();
        }

        // retrieve file
        if($method == 'GET') {
            // if key is id show meta file
            if($data = F::get($key)) {
                return $data;
            }

            // if key is uuid impose raw file
            if($data = self::getRawFile($key)) {
                return $data;
            }

            return Helper::error(array(
                'msg' => $key . ' not found'));
        }
        
        return Helper::error(array(
            'msg' => 'method not allowed'));
    }
    
    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
    private static function upload()
    {
        $file = current(Helper::getFile());
        $path = self::getPath();

        // init Upload
        $upload = new Upload();
        $upload->setPath($path)
            ->setAllowedMime(self::$allowedMime);

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
            return Helper::error(array('msg' => $e->getMessage()));
        }

        return $result;
    }

    private static function getRawFile($uuid)
    {   
        // search uuid if exists
        $data = F::get(array(
            'filters' => array(
                'uuid' => $uuid)));

        // dispose file
        if($data) {
            $path = self::getPath() . '/' . $uuid . '.' . $data['extension'];
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

    private static function getPath() {
        return control()->path(self::$filePath);
    }
}
