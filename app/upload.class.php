<?php
include_once(dirname(__FILE__) . "/../config.php");

class Upload {
    const kRet_Success = 0;
    const kRet_DataError = 1;
    const kRet_PathError = 2;
    const kRet_WriteError = 3;

    public static function uploadFileViaFileReader($dir, $data = null, $callback = null, $args = null) {
        if ($data == null) {
            $data = get_request_assert("data");
        } else if (substr($data, 0, 5) != "data:") {
            $data = get_request_assert($data);
        }
        // logging::d("Debug", $data);

        if (substr($data, 0, 5) != "data:") {
            logging::d("Debug", "data error 1.");
            return self::kRet_DataError;
        }

        // data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgIC…gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z
        // data:;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgIC…gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z
        $arr = explode(";", $data);
        if (count($arr) != 2) {
            logging::d("Debug", "data error 2.");
            return self::kRet_DataError;
        }

        $arr1 = explode(":", $arr[0]);
        if (count($arr1) == 2) {
            $type = $arr1[1];
            $type = explode('/', $type);
            if (isset($type[1])) {
                $extension = $type[1];
            } else {
                $extension = "bin";
            }
        } else {
            $extension = "bin";
            // logging::d("Debug", "data error 3.");
            // return self::kRet_DataError;
        }

        $arr = explode('base64,', $data);
        $image_content = base64_decode($arr[1]);

        logging::d("Debug", $dir);
        if (!file_exists($dir)) {
            $ret = @mkdir($dir, 0777, true);
            if ($ret === false) {
                return self::kRet_PathError;
            }
        }

        $filename = md5($image_content) . ".$extension";

        logging::d("Debug", $filename);

        $filepath = "$dir/$filename";
        if (!file_put_contents($filepath, $image_content)) {
            return self::kRet_WriteError;
        }
        if ($callback != null) {
            return $callback($filename, $args);
        }
        return self::kRet_Success;
    }

    public static function uploadImageViaFileReader($imgsrc = null, $callback = null, $args = null) {
        $whitelist = array("image/jpeg", "image/pjpeg", "image/png", "image/x-png", "image/gif");

        if ($imgsrc == null) {
            $imgsrc = get_request_assert("imgsrc");
        } else if (substr($imgsrc, 0, 5) != "data:") {
            $imgsrc = get_request_assert($imgsrc);
        }

        // data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQIBAQICAgICAgIC…gAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooA//Z
        $arr = explode(";", $imgsrc);
        if (count($arr) != 2) {
            return "fail|数据错误.";
        }

        $arr1 = explode(":", $arr[0]);
        if (count($arr1) != 2) {
            return "fail|数据错误..";
        }
        $type = $arr1[1];
        if (!in_array($type, $whitelist)) {
            return "fail|不支持的文件格式: $type.";
        }

        $type = explode('/', $type);
        $extension = $type[1];

        $arr = explode('base64,', $imgsrc);
        $image_content = base64_decode($arr[1]);

        if (!file_exists(UPLOAD_DIR)) {
            $ret = @mkdir(UPLOAD_DIR, 0777, true);
            if ($ret === false) {
                return "fail|上传目录创建失败.";
            }
        }

        $filename = md5($image_content) . ".$extension";

        $filepath = UPLOAD_DIR . "/$filename";
        if (!file_put_contents($filepath, $image_content)) {
            return 'fail|创建文件失败.';
        }
        if ($callback != null) {
            return $callback($filename, $args);
        }
        return "success";
    }
};
