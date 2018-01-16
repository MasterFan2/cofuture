<?php
/**
 * 配置信息
 * Created by MasterFan.
 * User: MasterFan
 * Date: 2017/11/22
 * Time: 15:58
 */

//数据库类型，用于PDO数据库连接
defined('DB_TYPE') or define('DB_TYPE', 'mysql');

//数据库是否需要保持长期连接（长连接）,多线程高并发环境下请开启,默认关闭
defined('DB_PERSISTENT_CONNECTION') or define('DB_PERSISTENT_CONNECTION', FALSE);

defined("HOST") or define("HOST", "192.168.0.50");//192.168.0.50 | 18.221.177.37

defined("PORT") or define("PORT", 3306);

defined("DB_NAME") or define("DB_NAME", "co_future");

defined("DB_USER") or define("DB_USER", "root");

defined("DB_PWD") or define("DB_PWD", "ailgxk");

defined("PAGE_SIZE") or define("PAGE_SIZE", 10);//第页显示多少条

defined("DEBUG") or define("DEBUG", true);