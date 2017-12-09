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

    public function edituser_action() {
        $uid = get_request_assert("uid");
        $username = get_request_assert("username");
        $password = get_request_assert("password");
        $nickname = get_request_assert("nickname");
        $telephone = get_request_assert("telephone");
        $email = get_request_assert("email");
        $comments = get_request_assert("comments");

        if ($uid == 1) {
            return $this->result(self::kRet_Fail);
        }


        $user = User::create($uid);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setNickname($nickname);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setComments($comments);
        $user->save();
        return $this->op("edituser", $user->packInfo());
    }

    public function setusergroup_action() {
        $uid = get_request_assert("uid");
        $gid = get_request_assert("gid");
        $join = get_request_assert("join");

        if ($uid == 1) {
            return $this->result(self::kRet_Fail);
        }

        $user = User::create($uid);
        if ($join == 1) {
            $user->joinGroup($gid);
        } else if ($join == 0) {
            $user->leaveGroup($gid);
        } else {
            logging::fatal("API", "no such operation.");
        }
        $user->save();
        return $this->result(self::kRet_Success);
    }

    public function removeuser_action() {
        $uid = get_request_assert("uid");
        if ($uid == 1) {
            return $this->result(self::kRet_Fail);
        }
        $ret = User::remove($uid);
        if ($ret !== false) {
            return $this->result(self::kRet_Success);
        } else {
            return $this->result(self::kRet_Fail);
        }
    }

}













