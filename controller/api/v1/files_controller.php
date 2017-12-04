<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class files_controller extends v1_base {
    public function preaction($action) {
        // login::assertPerm(Permission::kUser);
    }

    public function list_action() {
        $users = User::all(false);
        $data = $this->packArray($users);
        return array("op" => "users", "data" => $data);
    }

    public function adduser_action() {
        $username = get_request_assert("username");
        $password = get_request_assert("password");
        $nickname = get_request_assert("nickname");
        $telephone = get_request_assert("telephone");
        $email = get_request_assert("email");
        $comments = get_request_assert("comments");

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setNickname($nickname);
        $user->setTelephone($telephone);
        $user->setEmail($email);
        $user->setComments($comments);
        $user->save();
        return $this->op("newuser", $user->packInfo());
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













