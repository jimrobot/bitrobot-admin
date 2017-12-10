<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class files_controller extends v1_base {
    public function preaction($action) {
        // login::assertPerm(Permission::kUser);
    }

    public function list_action() {
        $files = Files::all(false);
        $data = $this->packArray($files);
        return array("op" => "files", "data" => $data);
    }

    public function upload_action() {
        logging::d("Debug", $_REQUEST);
        $arg = get_request_assert("arg");
        $filename = $arg["filename"];
        $title = $arg["title"];
        $comments = $arg["comments"];

        $dt = Date("Ym");
        $dir = UPLOAD_DIR . "/$dt";

        logging::d("Debug", "save upload files to $dir.");

        $args = array(
            "filename" => $filename,
            "title" => $title,
            "comments" => $comments,
            "dt" => $dt,
        );

        $ret = Upload::uploadFileViaFileReader($dir, null, function($filename, $args) {
            $dt = $args["dt"];
            $path = "$dt/$filename";

            $file = new Files();
            $file->setFilename($args["filename"]);
            $file->setPath($path);
            $file->setTitle($args["title"]);
            $file->setComments($args["comments"]);
            $file->save();
        }, $args);
    }

    public function edit_action() {
        $fid = get_request_assert("fid");
        $filename = get_request_assert("filename");
        $title = get_request_assert("title");
        $comments = get_request_assert("comments");

        $u = Files::create($fid);
        $u->setFilename($filename);
        $u->setTitle($title);
        $u->setComments($comments);
        $u->save();
        return $this->op("edit", $u->packInfo());
    }

    public function remove_action() {
        $fid = get_request_assert("fid");
        $ret = Files::remove($fid);
        if ($ret !== false) {
            return $this->result(self::kRet_Success);
        } else {
            return $this->result(self::kRet_Fail);
        }
    }

}













