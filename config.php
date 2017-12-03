<?php

if (file_exists(dirname(__FILE__) . "/../PATH.php")) {
    include_once(dirname(__FILE__) . "/../PATH.php");
}

include_once(dirname(__FILE__) . "/../framework/config.php");

include_once(FRAMEWORK_PATH . "/helper.php");
include_once(FRAMEWORK_PATH . "/logging.php");
include_once(FRAMEWORK_PATH . "/tpl.php");
include_once(FRAMEWORK_PATH . "/database.php");
include_once(FRAMEWORK_PATH . "/cache.php");

include_once(dirname(__FILE__) . "/database/db_user.class.php");
include_once(dirname(__FILE__) . "/database/db_setting.class.php");

include_once(dirname(__FILE__) . "/app/setting.class.php");
include_once(dirname(__FILE__) . "/app/user.class.php");
include_once(dirname(__FILE__) . "/app/login.class.php");


// database
defined('MYSQL_SERVER') or define('MYSQL_SERVER', '114.215.82.75');
defined('MYSQL_USERNAME') or define('MYSQL_USERNAME', 'root');
defined('MYSQL_PASSWORD') or define('MYSQL_PASSWORD', 'Ioriesy');
defined('MYSQL_DATABASE') or define('MYSQL_DATABASE', 'bitrobot');
defined('MYSQL_PREFIX') or define('MYSQL_PREFIX', 'bitrobot_');

defined("TEMP_PATH") or define("TEMP_PATH", APP_PATH . "/temp/");
defined("ALLOW_ROOT") or define("ALLOW_ROOT", false);


